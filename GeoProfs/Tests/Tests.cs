using NUnit.Framework;
using NUnit;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
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


        [SetUp]
        public void Setup()
        {
            using var conn = new MySqlConnection(connString);
            conn.Open();
            userManager = new UserManager(conn);
            database = new Database(conn);
            testUser = new EmployeeUser("Angelina", "Knoop", "AngelinaKnoop@gmail.com", "rocky123", "employee", 123, DateTime.Parse("2023-02-17"), 0);





        }

        [Test]
        public void FirstNameDataCorrect()
        {
            bool testSucceeded = true;
            if (testUser.FirstName != userTestValues["firstName"])
            {
                testSucceeded = false;
            }
            Assert.That(testSucceeded, Is.True);

        }
        [Test]
        public void LastNameDataCorrect() {

           
            Assert.That(testUser.LastName == userTestValues["lastName"], Is.True);

        }
        [Test]
        public void MailDataCorrect()
        {

         
            Assert.That(testUser.Email == userTestValues["email"], Is.True);

        }
    }
}
