using NUnit.Framework;
using System;
using System.IO;
using System.Linq;
using MySqlConnector;
using GeoProfs;

namespace GeoProfs.Tests
{
    [TestFixture]
    public class EndToEndTests
    {
        private MySqlConnection conn;
        private Database db;

        [SetUp]
        public void Setup()
        {
            var connString = "Server=q0t164.h.filess.io;Port=3305;" +
                             "Uid=geoprofs_magicfind;" +
                             "Pwd=24621c3ce4a7d2fd3aae4aafe468aebe432f5d82;" +
                             "Database=geoprofs_magicfind;";

            // assign to the class field, not a local variable
            conn = new MySqlConnection(connString);
            conn.Open();
            db = new Database(conn);
        }

        [TearDown]
        public void Cleanup()
        {
            if (db != null && conn != null && conn.State == System.Data.ConnectionState.Open)
            {
                var users = db.getAllUsers().Where(u => u.Email == "john@example.com").ToList();
                foreach (var u in users)
                {
                    db.DeleteUser(u.ID); // assuming you have a DeleteUser method
                }
                conn.Close();
            }
        }

        [Test]
        public void EndToEnd_UserCreation_SavesToDatabase()
        {
            // Simulate console input for creating a new employee
            var input = new StringReader(
                "employee\n" +          // role
                "John\n" +              // first name
                "Doe\n" +               // last name
                "john@example.com\n" +  // email
                "pwd\n" +               // password
                "123\n" +               // employee number
                "2025-01-01\n" +        // start date
                "12\n" +                // leave days
                "0\n" +                 // department id
                "0\n"                   // supervisor id
            );
            Console.SetIn(input);

            var userManager = new UserManager(conn);

            // Act: run the actual creation flow
            userManager.CreateUser();

            // Assert: verify user was saved in DB
            var users = db.getAllUsers();
            Assert.That(users.Any(u => u.Email == "john@example.com"), Is.True);
        }
    }
}
