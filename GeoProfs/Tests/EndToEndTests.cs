using GeoProfs;
using GeoProfs.SessionData;
using MySqlConnector;
using NUnit.Framework;
using System;
using System.Diagnostics;
using System.IO;
using System.Linq;
using System.Threading.Tasks;

namespace GeoProfs.Tests
{
    [TestFixture]
    public class EndToEndTests
    {
        private MySqlConnection conn;
        private Database db;

        private readonly string exePath =
            @"C:\Users\nijme\source\repos\GeoProfs\GeoProfs\bin\Debug\net8.0\GeoProfs.exe";

        [SetUp]
        public void Setup()
        {
            var connString =
                "Server=q0t164.h.filess.io;Port=3305;" +
                "Uid=geoprofs_magicfind;" +
                "Pwd=24621c3ce4a7d2fd3aae4aafe468aebe432f5d82;" +
                "Database=geoprofs_magicfind;";

            conn = new MySqlConnection(connString);
            conn.Open();
            db = new Database(conn);
        }

        [TearDown]
        public void Cleanup()
        {
            
            try
            {
                if (db == null || conn == null)
                    return;

                if (conn.State != System.Data.ConnectionState.Open)
                    return;

                var users = db.getAllUsers();
                if (users == null)
                    return;

                foreach (var u in users)
                {
                    if (u != null && u.Email == "john@example.com")
                    {
                        try
                        {
                            db.DeleteUser(u.ID);
                        }
                        catch
                        {
                           
                        }
                    }
                }
            }
            catch
            {
                
            }
            finally
            {
                try
                {
                    conn?.Close();
                }
                catch { }
            }
        }

        private async Task<(string output, string error)> RunCliAsync(params string[] inputs)
        {
            Assert.That(File.Exists(exePath), $"EXE not found at: {exePath}");

            var process = new Process
            {
                StartInfo = new ProcessStartInfo
                {
                    FileName = exePath,
                    RedirectStandardInput = true,
                    RedirectStandardOutput = true,
                    RedirectStandardError = true,
                    UseShellExecute = false,
                    CreateNoWindow = true
                }
            };

            process.Start();

            using (var sw = process.StandardInput)
            {
                foreach (var input in inputs)
                {
                    await sw.WriteLineAsync(input);
                }
            }

            string output = await process.StandardOutput.ReadToEndAsync();
            string error = await process.StandardError.ReadToEndAsync();

            process.WaitForExit();
            return (output, error);
        }

        [Test]
        public async Task EndToEnd_Login_Fails_ShowsRetryMessage()
        {
            var (output, error) = await RunCliAsync(
                "Test",
                "Test"
            );

            Assert.That(error, Is.Empty);
            Assert.That(output, Does.Contain("=== GeoProfs Login ==="));
            Assert.That(output, Does.Contain("Login failed. Try again."));
        }

        [Test]
        public async Task EndToEnd_ExitImmediately()
        {
            var (output, error) = await RunCliAsync(
                "elco@f",
                "af",
                "0"
            );

            Assert.That(error, Is.Empty);
            Assert.That(output, Does.Contain("=== GeoProfs Login ==="));
        }

        [Test]
        public async Task EndToEnd_InvalidMenuChoice_ShowsError()
        {
            var (output, error) = await RunCliAsync(
                "elco@f",
                "af",
                "999"
            );

            Assert.That(error, Is.Empty);
            Assert.That(output, Does.Contain("Invalid choice."));
        }
        
    }
}
