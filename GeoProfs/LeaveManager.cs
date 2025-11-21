using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using MySqlConnector;
using GeoProfs.Enums;
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
        private List<LeaveRequest> FilterPendingLeaveRequests(List<LeaveRequest> allLeaveReq) {

            return allLeaveReq.Where(leaveReq => leaveReq.Status == LeaveEnums.LeaveStatus.pending).ToList();
        
        }
        // [F]  
        public void ManageLeaveRequests() {
           
            bool running = true;
            while (running)
            {
                List<LeaveRequest> leaveRequestsPending = FilterPendingLeaveRequests(database.GetAllLeaveRequests());
                if (leaveRequestsPending.Count == 0) {
                    Console.WriteLine("No leave requests found.");
                    running = false;
                    break;
                
                }
                for (int i = 0; i < leaveRequestsPending.Count; i++)
                {
                    DisplayUser currentUser = database.GetUserFromID(leaveRequestsPending[i].UserId);
                    Console.WriteLine($"" +
                        $"Name: {currentUser.FirstName} {currentUser.LastName}" +
                        $"\nDate: {leaveRequestsPending[i].StartDate} ---- {leaveRequestsPending[i].EndDate}" +
                        $"\nLeave days left: {currentUser.LeaveDaysPerYear}" +
                        $"\nPosition: {currentUser.Position}" +
                        $"\n[{i}] Manage request\n");

                }
                int choice;
                bool success = int.TryParse(Console.ReadLine(), out choice);

                DisplayUser chosenUser = database.GetUserFromID(leaveRequestsPending[choice].UserId);
                if (success)
                {
                    Console.WriteLine($"[1] Accept\n[2] Deny\n[3] Back");
                    int choice2;
                    bool success2 = int.TryParse(Console.ReadLine(), out choice2);
                    if (success2) {
                        switch (choice2) {
                            case 1:
                                database.ChangeLeaveStatus(leaveRequestsPending[choice].Id, LeaveEnums.LeaveStatus.accepted);
                                emailService.SendLeaveStatusUpdateEmail(chosenUser.Email, LeaveEnums.LeaveStatus.accepted, leaveRequestsPending[choice]);
                                break;
                            case 2:
                                database.ChangeLeaveStatus(leaveRequestsPending[choice].Id, LeaveEnums.LeaveStatus.denied);

                                emailService.SendLeaveStatusUpdateEmail(chosenUser.Email, LeaveEnums.LeaveStatus.denied, leaveRequestsPending[choice]);
                                break;
                            case 3:
                               
                                break;
                        
                        }
                    
                    }
                }
                else { Console.WriteLine("Not an option"); }
            }




        
        }
    }
}
