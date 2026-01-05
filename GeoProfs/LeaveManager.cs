using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using MySqlConnector;
using GeoProfs.Enums;
using GeoProfs.SessionData;
namespace GeoProfs
{
    public class LeaveManager
    {
        Database database;
        MySqlConnection conn;
        EmailService emailService = new();
        public LeaveManager(MySqlConnection conn)
        {
            this.conn = conn;
            this.database = new Database(conn);
        }
        private List<LeaveRequest> FilterPendingLeaveRequests(List<LeaveRequest> allLeaveReq)
        {

            return allLeaveReq.Where(leaveReq => leaveReq.Status == LeaveEnums.LeaveStatus.pending).ToList();

        }

        public void ManageLeaveRequests()
        {
            if (SessionUser.sessionUser == null)
            {
                Console.WriteLine("Not logged in");
                return;
            }
            bool bulkAccepted = false;
            bool running = true;
            while (running)
            {
                List<LeaveRequest> leaveRequestsPending = FilterPendingLeaveRequests(database.GetAllLeaveRequests());
                if (leaveRequestsPending.Count == 0)
                {
                    Console.WriteLine("No leave requests found.");
                    running = false;
                    break;

                }
                for (int i = 0; i < leaveRequestsPending.Count; i++)
                {
                    DisplayUser currentusers = database.GetUserFromID(leaveRequestsPending[i].UserId);
                    if (currentusers.Department != SessionUser.sessionUser.Department) { continue; }
                    Console.WriteLine($"" +
                        $"Name: {currentusers.FirstName} {currentusers.LastName}" +
                        $"\nDate: {leaveRequestsPending[i].StartDate} ---- {leaveRequestsPending[i].EndDate}" +
                        $"\nLeave days left: {currentusers.LeaveDaysPerYear}" +
                        $"\nPosition: {currentusers.Department}" +
                        $"\nReason: {leaveRequestsPending[i].Reason}" +
                        $"\n[{i}] Manage request\n");

                }
                bool success = true;
                Console.WriteLine("[B] Bulk accept");
                string input = Console.ReadLine();
                List<int> choices = new List<int>();
                int choice = 1;
                if (input == "b")
                {
                    bulkAccepted = true;
                    Console.WriteLine("Input numbers 1 by 1, type 'end' when you want to end");
                    bool run = true;
                    while (run)
                    {
                        string input1 = Console.ReadLine();
                        if (input1 == "end") { break; }
                        int n;

                        bool suc = int.TryParse(input1, out n);
                        if (!suc) { Console.WriteLine("invalid option"); }
                        else
                        {
                            choices.Add(n);

                        }


                    }

                }

                else
                {
                    bulkAccepted = false;
                    success = int.TryParse(input, out choice);
                }

                if (success && !bulkAccepted)
                {

                    DisplayUser chosenusers = database.GetUserFromID(leaveRequestsPending[choice].UserId);
                    Console.WriteLine($"[1] Accept\n[2] Deny\n[3] Back");
                    int choice2;
                    bool success2 = int.TryParse(Console.ReadLine(), out choice2);
                    if (success2)
                    {
                        switch (choice2)
                        {
                            case 1:
                                database.ChangeLeaveStatus(leaveRequestsPending[choice].Id, LeaveEnums.LeaveStatus.accepted);
                                emailService.SendLeaveStatusUpdateEmail(chosenusers.Email, LeaveEnums.LeaveStatus.accepted, leaveRequestsPending[choice]);
                                SessionUser.audit_trail.SaveActionToAuditTrail("Accepted leave request");
                                break;
                            case 2:
                                database.ChangeLeaveStatus(leaveRequestsPending[choice].Id, LeaveEnums.LeaveStatus.denied);

                                emailService.SendLeaveStatusUpdateEmail(chosenusers.Email, LeaveEnums.LeaveStatus.denied, leaveRequestsPending[choice]);
                                SessionUser.audit_trail.SaveActionToAuditTrail("Denied leave request");
                                break;
                            case 3:

                                break;

                        }

                    }
                }
                else if (success && bulkAccepted)
                {

                    Console.WriteLine($"[1] Accept\n[2] Deny\n[3] Back");
                    int choice2;
                    bool success2 = int.TryParse(Console.ReadLine(), out choice2);
                    if (success2)
                    {
                        switch (choice2)
                        {
                            case 1:
                                foreach (int n in choices)
                                {

                                    DisplayUser chosenusers = database.GetUserFromID(leaveRequestsPending[n].UserId);
                                    database.ChangeLeaveStatus(leaveRequestsPending[n].Id, LeaveEnums.LeaveStatus.accepted);
                                    emailService.SendLeaveStatusUpdateEmail(chosenusers.Email, LeaveEnums.LeaveStatus.accepted, leaveRequestsPending[n]);
                                }
                                break;
                            case 2:
                                foreach (int n in choices)
                                {
                                    DisplayUser chosenusers = database.GetUserFromID(leaveRequestsPending[n].UserId);
                                    database.ChangeLeaveStatus(leaveRequestsPending[n].Id, LeaveEnums.LeaveStatus.denied);
                                    emailService.SendLeaveStatusUpdateEmail(chosenusers.Email, LeaveEnums.LeaveStatus.accepted, leaveRequestsPending[n]);
                                }
                                break;
                            case 3:

                                break;

                        }

                    }

                }
                else { Console.WriteLine("Not an option"); }
            }
        }

            public List<LeaveRequest> GetAcceptedLeaveRequests()
        {
            if (SessionUser.sessionUser == null)
            {
                Console.WriteLine("Not logged in");
                return new List<LeaveRequest>();
            }

            List<LeaveRequest> allLeaveRequests = database.GetAllLeaveRequests();

            List<LeaveRequest> acceptedRequests = allLeaveRequests
                .Where(leaveReq =>
                {
                    if (leaveReq.Status != LeaveEnums.LeaveStatus.accepted)
                        return false;

                    DisplayUser user = database.GetUserFromID(leaveReq.UserId);
                    if (user == null)
                        return false;

                    return user.Department == SessionUser.sessionUser.Department;
                })
                .ToList();

            return acceptedRequests;
        }
        public void ShowAcceptedLeaveRequests()
        {
            var acceptedRequests = GetAcceptedLeaveRequests();
            if (acceptedRequests.Count == 0)
            {
                Console.WriteLine("No accepted leave requests found in your department.");
                return;
            }

            foreach (var leave in acceptedRequests)
            {
                DisplayUser user = database.GetUserFromID(leave.UserId);
                Console.WriteLine(
                    $"Name: {user.FirstName} {user.LastName}\n" +
                    $"Date: {leave.StartDate:yyyy-MM-dd} ---- {leave.EndDate:yyyy-MM-dd}\n" +
                    $"Leave days left: {user.LeaveDaysPerYear}\n" +
                    $"Position: {user.Department}\n" +
                    $"Reason: {leave.Reason}\n" +
                    $"Status: {leave.Status}\n" +
                    "-------------------------------------"
                );
            }
        }


    }
}

