using System;
using GeoProfs;
using MySqlConnector;
using GeoProfs.SessionData;

class Program
{
    static void Main()
    {
        var connString = "Server=q0t164.h.filess.io;Port=3305;" +
                         "User Id=geoprofs_magicfind;" +
                         "Password=24621c3ce4a7d2fd3aae4aafe468aebe432f5d82;" +
                         "Database=geoprofs_magicfind;";

        using var conn = new MySqlConnection(connString);
        if (conn.State != System.Data.ConnectionState.Open)
            conn.Open();

        Database database = new(conn);
        UserManager userManager = new(conn);
        LeaveManager leaveM = new(conn);
        SessionManager sessionManager = new(conn);

       
        while (SessionUser.sessionUser == null)
        {
            Console.Clear();
            Console.WriteLine("=== GeoProfs Login ===");
            bool success = sessionManager.Login();
            
            if (!success)
            {
                Console.WriteLine("Login failed. Try again.");
                Console.WriteLine("Press any key to retry...");
                Console.ReadKey();
            }
        }

        // Once logged in, show menu
        bool exit = false;
        while (!exit)
        {
            Console.Clear();
            Console.WriteLine($"=== GeoProfs CLI Menu (Logged in as {SessionUser.sessionUser.FirstName}) ===");
            Console.WriteLine("1. Create User");
            Console.WriteLine("2. Delete User");
            Console.WriteLine("3. Manage Leave Requests");
            Console.WriteLine("4. Adjust App Settings");
            Console.WriteLine("0. Exit");
            Console.WriteLine("=========================");
            Console.Write("Select an option: ");

            string? choice = Console.ReadLine();

            switch (choice)
            {
                case "1":
                    userManager.CreateUser();
                    break;
                case "2":
                    userManager.DeleteUser();
                    break;
                case "3":
                    leaveM.ManageLeaveRequests();
                    break;
                case "4":
                    AppSettings.AdjustSettings();
                    break;
                case "0":
                    exit = true;
                    break;
                default:
                    Console.WriteLine("Invalid choice. Try again.");
                    break;
            }

            if (!exit)
            {
                Console.WriteLine("\nPress any key to return to menu...");
                Console.ReadKey();
            }
        }
    }
}
