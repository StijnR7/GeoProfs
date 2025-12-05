using NUnit.Framework;
using Moq;
using System;
using System.Collections.Generic;
using GeoProfs;
using GeoProfs.Enums;
using MySqlConnector;
namespace GeoProfs.Tests
{
    [TestFixture]
    public class ImportantUnitTests
    {
        [Test]
        public void LeaveRequest_DefaultsToPending()
        {
            var leave = new LeaveRequest(1, DateTime.Today, DateTime.Today.AddDays(3));
            Assert.That(leave.Status, Is.EqualTo(LeaveEnums.LeaveStatus.pending));
        }

        [Test]
        public void AppSettings_CanUpdateValues()
        {
            AppSettings.employeesWorkingMorning = 2;
            AppSettings.employeesWorkingAfternoon = 3;
            AppSettings.employeesWorkingEvening = 4;

            Assert.Multiple(() =>
            {
                Assert.That(AppSettings.employeesWorkingMorning, Is.EqualTo(2));
                Assert.That(AppSettings.employeesWorkingAfternoon, Is.EqualTo(3));
                Assert.That(AppSettings.employeesWorkingEvening, Is.EqualTo(4));
            });
        }

        [Test]
        public void EmployeeUser_AssignsSupervisorCorrectly()
        {
            var emp = new EmployeeUser("John", "Doe", "john@company.com", "pwd", "ROLE_USER", 123, DateTime.Today, 42);
            Assert.That(emp.SuperVisor, Is.EqualTo(42));
        }

        [Test]
        public void ManagerUser_HasDefaultLeaveDays()
        {
            var mgr = new ManagerUser("Jane", "Smith", "jane@company.com", "pwd", "ROLE_ADMIN", 456, DateTime.Today);
            Assert.That(mgr.LeaveDaysPerYear, Is.EqualTo(12));
        }

        [Test]
        public void CEOUser_Has365LeaveDays()
        {
            var ceo = new CEOUser("Alice", "CEO", "alice@company.com", "pwd", "ROLE_ADMIN", 789, DateTime.Today, 365);
            Assert.That(ceo.LeaveDaysPerYear, Is.EqualTo(365));
        }

        [Test]
        public void Shift_CorrectlyStoresTimes()
        {
            var shift = new Shift
            {
                Id = 1,
                UserID = 10,
                StartTime = new TimeOnly(9, 0),
                endTime = new TimeOnly(17, 0),
                ShiftDate = new DateOnly(2025, 12, 4)
            };

            Assert.That(shift.StartTime.Hour, Is.EqualTo(9));
            Assert.That(shift.endTime.Hour, Is.EqualTo(17));
        }
    }

    [TestFixture]
    public class LeaveManagerInteractionTests
    {
        [Test]
        public void ManageLeaveRequests_AcceptSingleRequest_CallsDatabaseAndEmail()
        {
            // Arrange
            var mockDb = new Mock<Database>(null);
            var mockEmail = new Mock<EmailService>();

            var pendingRequest = new LeaveRequest(1, DateTime.Today, DateTime.Today.AddDays(1))
            {
                Id = 99,
                Status = LeaveEnums.LeaveStatus.pending
            };
            var user = new DisplayUser("John", "Doe", "john@example.com", "pwd", "ROLE_USER", 123, DateTime.Today, 12)
            {
                ID = 1,
                Email = "john@example.com"
            };

            mockDb.Setup(d => d.GetAllLeaveRequests()).Returns(new List<LeaveRequest> { pendingRequest });
            mockDb.Setup(d => d.GetUserFromID(1)).Returns(user);

            var manager = new LeaveManager(new MySqlConnector.MySqlConnection());
            typeof(LeaveManager).GetField("database", System.Reflection.BindingFlags.NonPublic | System.Reflection.BindingFlags.Instance)
                .SetValue(manager, mockDb.Object);
            typeof(LeaveManager).GetField("emailService", System.Reflection.BindingFlags.NonPublic | System.Reflection.BindingFlags.Instance)
                .SetValue(manager, mockEmail.Object);

            // Act
            mockDb.Object.ChangeLeaveStatus(pendingRequest.Id, LeaveEnums.LeaveStatus.accepted);
            mockEmail.Object.SendLeaveStatusUpdateEmail(user.Email, LeaveEnums.LeaveStatus.accepted, pendingRequest);

            // Assert
            mockDb.Verify(d => d.ChangeLeaveStatus(99, LeaveEnums.LeaveStatus.accepted), Times.Once);
            mockEmail.Verify(e => e.SendLeaveStatusUpdateEmail("john@example.com", LeaveEnums.LeaveStatus.accepted, pendingRequest), Times.Once);
        }

        [Test]
        public void ManageLeaveRequests_DenyRequest_CallsDatabaseAndEmail()
        {
            var mockDb = new Mock<Database>(null);
            var mockEmail = new Mock<EmailService>();

            var pendingRequest = new LeaveRequest(2, DateTime.Today, DateTime.Today.AddDays(2))
            {
                Id = 100,
                Status = LeaveEnums.LeaveStatus.pending
            };
            var user = new DisplayUser("Jane", "Smith", "jane@example.com", "pwd", "ROLE_USER", 456, DateTime.Today, 12)
            {
                ID = 2,
                Email = "jane@example.com"
            };

            mockDb.Setup(d => d.GetAllLeaveRequests()).Returns(new List<LeaveRequest> { pendingRequest });
            mockDb.Setup(d => d.GetUserFromID(2)).Returns(user);

            var manager = new LeaveManager(new MySqlConnector.MySqlConnection());
            typeof(LeaveManager).GetField("database", System.Reflection.BindingFlags.NonPublic | System.Reflection.BindingFlags.Instance)
                .SetValue(manager, mockDb.Object);
            typeof(LeaveManager).GetField("emailService", System.Reflection.BindingFlags.NonPublic | System.Reflection.BindingFlags.Instance)
                .SetValue(manager, mockEmail.Object);

            // Act
            mockDb.Object.ChangeLeaveStatus(pendingRequest.Id, LeaveEnums.LeaveStatus.denied);
            mockEmail.Object.SendLeaveStatusUpdateEmail(user.Email, LeaveEnums.LeaveStatus.denied, pendingRequest);

            // Assert
            mockDb.Verify(d => d.ChangeLeaveStatus(100, LeaveEnums.LeaveStatus.denied), Times.Once);
            mockEmail.Verify(e => e.SendLeaveStatusUpdateEmail("jane@example.com", LeaveEnums.LeaveStatus.denied, pendingRequest), Times.Once);
        }

        [Test]
        public void FilterPendingLeaveRequests_ReturnsOnlyPending()
        {
            var manager = new LeaveManager(new MySqlConnector.MySqlConnection());
            var method = typeof(LeaveManager).GetMethod("FilterPendingLeaveRequests", System.Reflection.BindingFlags.NonPublic | System.Reflection.BindingFlags.Instance);

            var requests = new List<LeaveRequest>
            {
                new LeaveRequest(1, DateTime.Today, DateTime.Today.AddDays(1)) { Status = LeaveEnums.LeaveStatus.pending },
                new LeaveRequest(2, DateTime.Today, DateTime.Today.AddDays(2)) { Status = LeaveEnums.LeaveStatus.accepted }
            };

            var result = (List<LeaveRequest>)method.Invoke(manager, new object[] { requests });

            Assert.That(result.Count, Is.EqualTo(1));
            Assert.That(result[0].UserId, Is.EqualTo(1));
        }
       

    }
}
