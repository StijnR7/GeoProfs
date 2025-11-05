using NUnit.Framework;
using System;
using System.Collections.Generic;
using MySqlConnector;

namespace GeoProfs.Tests
{
    [TestFixture]
    public class Tests
    {
        string connString = "Server=q0t164.h.filess.io;Port=3305;" +
        "User Id=geoprofs_magicfind;" +
        "Password=24621c3ce4a7d2fd3aae4aafe468aebe432f5d82;" +
        "Database=geoprofs_magicfind;";

        UserManager userManager;
        Database database;
        IUser testUser;
        Shift testShift;

        Dictionary<string, string> userTestValues = new Dictionary<string, string> {
            {"firstName", "Angelina"},
            {"lastName", "Knoop" },
            {"email", "AngelinaKnoop@gmail.com" },
            {"password", "rocky123" },
            { "role", "employee"},
            {"bsn", "123" },
            {"startDate", "2023-02-17" },
            {"superVisor", "0" }
        };

        Dictionary<string, string> shiftTestValues = new Dictionary<string, string>
        {
            {"id", "1"},
            {"userID", "5"},
            {"startTime", "08:30"},
            {"endTime", "17:00"},
            {"shiftDate", "2023-02-20"},
            {"position", "employee"}
        };

        [SetUp]
        public void Setup()
        {
            using var conn = new MySqlConnection(connString);
            conn.Open();

            userManager = new UserManager(conn);
            database = new Database(conn);

            testUser = new EmployeeUser(
                "Angelina",
                "Knoop",
                "AngelinaKnoop@gmail.com",
                "rocky123",
                "employee",
                123,
                DateTime.Parse("2023-02-17"),
                0
            );

            testShift = new Shift
            {
                Id = 1,
                UserID = 5,
                StartTime = TimeOnly.Parse("08:30"),
                endTime = TimeOnly.Parse("17:00"),
                ShiftDate = DateOnly.Parse("2023-02-20"),
                Position = GeoProfs.Enums.UserEnums.UserPositions.employee
            };
        }

        [Test]
        public void UserDataCorrect()
        {
            Assert.That(testUser.FirstName == userTestValues["firstName"], Is.True);
            Assert.That(testUser.LastName == userTestValues["lastName"], Is.True);
            Assert.That(testUser.Email == userTestValues["email"], Is.True);
            Assert.That(testUser.Password == userTestValues["password"], Is.True);

            Assert.That(testUser.Bsn == int.Parse(userTestValues["bsn"]), Is.True);

            Assert.That(testUser.StartDate.Date == DateTime.Parse(userTestValues["startDate"]).Date, Is.True);

            Assert.That(testUser.Position.ToString().ToLower() == userTestValues["role"], Is.True);

            Assert.That(testUser.SuperVisor == int.Parse(userTestValues["superVisor"]), Is.True);
        }

        [Test]
        public void ShiftDataCorrect()
        {
            Assert.That(testShift.Id == int.Parse(shiftTestValues["id"]), Is.True);
            Assert.That(testShift.UserID == int.Parse(shiftTestValues["userID"]), Is.True);

            Assert.That(testShift.StartTime.ToString("HH:mm") == shiftTestValues["startTime"], Is.True);
            Assert.That(testShift.endTime.ToString("HH:mm") == shiftTestValues["endTime"], Is.True);

            Assert.That(testShift.ShiftDate == DateOnly.Parse(shiftTestValues["shiftDate"]), Is.True);

            Assert.That(testShift.Position.ToString().ToLower() == shiftTestValues["position"], Is.True);
        }
    }
}
