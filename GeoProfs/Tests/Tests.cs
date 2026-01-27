using GeoProfs.Enums;
using NUnit.Framework;
using System;
using System.Collections.Generic;

namespace GeoProfs.Tests
{
    [TestFixture]
    public class SimpleUnitTests
    {
        // Test 1: basic object creation
        [Test]
        public void LeaveRequest_ShouldDefaultToPending()
        {
            var leave = new LeaveRequest(1, DateTime.Today, DateTime.Today.AddDays(3));
            Assert.That(leave.Status, Is.EqualTo(LeaveEnums.LeaveStatus.pending));
        }

        // Test 2: simple property check
        [Test]
        public void EmployeeUser_ShouldStoreSupervisorId()
        {
            var emp = new EmployeeUser("John", "Doe", "john@company.com", "pwd", "ROLE_USER", 123, DateTime.Today, 42);
            Assert.That(emp.SuperVisor, Is.EqualTo(42));
        }

        // Test 3: simple list filtering
        [Test]
        public void FilterPendingLeaves_ShouldReturnOnlyPending()
        {
            var leaves = new List<LeaveRequest>
            {
                new LeaveRequest(1, DateTime.Today, DateTime.Today.AddDays(1)) { Status = LeaveEnums.LeaveStatus.pending },
                new LeaveRequest(2, DateTime.Today, DateTime.Today.AddDays(2)) { Status = LeaveEnums.LeaveStatus.accepted }
            };

            var pending = leaves.FindAll(l => l.Status == LeaveEnums.LeaveStatus.pending);

            Assert.That(pending.Count, Is.EqualTo(1));
            Assert.That(pending[0].UserId, Is.EqualTo(1));
        }
    }
}
