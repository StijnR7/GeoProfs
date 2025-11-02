using NUnit.Framework;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using NUnit.Framework;
namespace GeoProfs.Tests
{
    [TestFixture]
    public class Tests
    {
        var connString = "Server=q0t164.h.filess.io;Port=3305;" +
     "User Id=geoprofs_magicfind;" +
     "Password=24621c3ce4a7d2fd3aae4aafe468aebe432f5d82;" +
     "Database=geoprofs_magicfind;";
        private Mock<MySqlConnection> _mockConnection;
        private Mock<MySqlCommand> _mockCommand;
        private Mock<MySqlDataReader> _mockReader;
        UserManager userManager;
        Database database;
        IUser testUser;

        [SetUp]
        public void Setup()
        {
            using var conn = new Mock<MySqlConnection>(connString);
            conn.Open();
            userManager = new UserManager(conn);
            database = new Database(conn);
            testUser =  = new EmployeeUser("Angelina", "Knoop", "AngelinaKnoop@gmail.com", "rocky123", "employee", "2023-02-17", "elco");
        }

        [Test]
        public void CreateValidUser()
        {
           
            
        }
    }
}
