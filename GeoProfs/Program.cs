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

        Audit_trail audit = new(conn);
        Database database = new(conn);
        UserManager userManager = new(conn);
        LeaveManager leaveM = new(conn);
        SessionManager sessionManager = new(conn);
        ShiftManager shiftManager = new(conn);

        // LOGIN LOOP
        while (SessionUser.sessionUser == null)
        {
            if (!Console.IsOutputRedirected)
                Console.Clear();

            Console.WriteLine("=== GeoProfs Login ===");

            bool success = sessionManager.Login();

            if (success)
                break; // login successful

            Console.WriteLine("Login failed. Try again.");

            if (!Console.IsInputRedirected)
            {
                Console.WriteLine("Press any key to retry...");
                Console.ReadKey();
            }
            else
            {
                // stop loop during E2E tests to avoid infinite looping
                break;
            }
        }

        SessionUser.audit_trail = audit;

        bool exit = false;
        while (!exit)
        {
            if (!Console.IsOutputRedirected)
                Console.Clear();

            Console.WriteLine($"=== GeoProfs CLI Menu (Logged in as {SessionUser.sessionUser?.FirstName ?? "Unknown"}) ===");
            Console.WriteLine("1. Create User");
            Console.WriteLine("2. Delete User");
            Console.WriteLine("3. Manage Leave Requests");
            Console.WriteLine("4. Adjust App Settings");
            Console.WriteLine("5. See audit trail");
            Console.WriteLine("6. Create Shift");
            Console.WriteLine("7. View All Shifts");
            Console.WriteLine("8. View Accepted Leave Requests");
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
                case "5":
                    audit.ShowAuditTrailData();
                    break;
                case "6":
                    shiftManager.AddNewShift();
                    break;
                case "7":
                    database.ShowAllShifts();
                    break;
                case "8":
                    leaveM.ShowAcceptedLeaveRequests();
                    break;
                case "0":
                    exit = true;
                    break;
                default:
                    Console.WriteLine("Invalid choice.");
                    break;
            }

            if (!exit)
            {
                if (!Console.IsInputRedirected)
                {
                    Console.WriteLine("\nPress any key to return to menu...");
                    Console.ReadKey();
                }
                else
                {
                    // stop E2E test from waiting indefinitely
                    break;
                }
            }
        }
    }
}
