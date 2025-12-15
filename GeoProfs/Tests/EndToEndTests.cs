using GeoProfs;
using MySqlConnector;
using NUnit.Framework;
using System;
using System.Diagnostics;
using System.IO;
using System.Linq;

namespace GeoProfs.Tests
{
    [TestFixture]
    public class EndToEndTests
    {
        private MySqlConnection conn;
        private Database db;
        private string projPath = "C:\\Users\\nijme\\source\\repos\\GeoProfs";
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

        async public void EndToEnd_Login() {
            var process = new Process
            {
                StartInfo = new ProcessStartInfo
                {
                    FileName = "dotnet",
                    Arguments = $"run --project \"{projPath}\"", // run the CLI project
                    RedirectStandardOutput = true,
                    RedirectStandardError = true,
                    UseShellExecute = false,
                    CreateNoWindow = true
                }
            };

            process.Start();

            // Write the two inputs (e.g., username and password)
            using (var sw = process.StandardInput)
            {
                if (sw.BaseStream.CanWrite)
                {
                    await sw.WriteLineAsync("Test");  // first input
                    await sw.WriteLineAsync("Test"); // second input
                }
            }

            // Read the output
            string output = await process.StandardOutput.ReadToEndAsync();
            string error = await process.StandardError.ReadToEndAsync();

            process.WaitForExit();

            // Assertions using Assert.That
            Assert.That(process.ExitCode, Is.EqualTo(0), $"CLI exited with error: {error}");
            Assert.That(output, Does.Contain("User not admin or doesnt exist"));
            Assert.That(output, Does.Contain("Login failed. Try again."));
            Assert.That(output, Does.Contain("Press any key to retry..."));

        }

    }
}
