using GeoProfs.Enums;
using GeoProfs.SessionData;
using MySqlConnector;
using MySqlX.XDevAPI.Relational;
using System;
using System.Collections;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using static Microsoft.ApplicationInsights.MetricDimensionNames.TelemetryContext;

namespace GeoProfs
{
    public class Database
    {
        MySqlConnection conn;

        public Database(MySqlConnection conn)
        {
            this.conn = conn;
        }


        public virtual void DeleteUser(int userId)
        {
            using var cmd = conn.CreateCommand();
            cmd.CommandText = "DELETE FROM user WHERE id = @id AND department = @dept";
            cmd.Parameters.AddWithValue("@id", userId);
            cmd.Parameters.AddWithValue("@dept", SessionUser.sessionUser.Department);
            cmd.ExecuteNonQuery();
        }
        public virtual void SaveAudit(int userId, string action, DateTime datetime)
        {
            using var cmd = conn.CreateCommand();
            cmd.CommandText = @"
        INSERT INTO audit_trail (user_id, action, date_time)
        VALUES (@userId, @action, @datetime);
    ";

            cmd.Parameters.AddWithValue("@userId", userId);
            cmd.Parameters.AddWithValue("@action", action);
            cmd.Parameters.AddWithValue("@datetime", datetime);

            cmd.ExecuteNonQuery();
        }


        public void ShowAllDataFromTable(DatabaseEnums.DatabaseTables table)
        {
            var cmd = new MySqlCommand($"SELECT * FROM `{table}`;", conn);
            var reader = cmd.ExecuteReader();

            while (reader.Read())
            {
                for (int i = 0; i < reader.FieldCount; i++)
                {
                    Console.Write($"{reader.GetName(i)}: {reader[i]}\n");
                }
                Console.WriteLine("\n");
            }
        }

        public virtual List<LeaveRequest> GetAllLeaveRequests()
        {
            var leaveRequests = new List<LeaveRequest>();
            string query = $"SELECT * FROM `{DatabaseEnums.DatabaseTables.leave}`;";

            using (var cmd = new MySqlCommand(query, conn))
            using (var reader = cmd.ExecuteReader())
            {
                while (reader.Read())
                {
                    var req = new LeaveRequest(
                        int.Parse(reader["userID"].ToString()),
                        DateTime.Parse(reader["leaveStart"].ToString()),
                        DateTime.Parse(reader["leaveEnd"].ToString())
                    );

                    Enum.TryParse(reader["status"].ToString(), out LeaveEnums.LeaveStatus currentStatus);
                    req.Reason = reader["reason"].ToString(); 
                    req.Status = currentStatus;

                    int leaveID;
                    if (int.TryParse(reader["id"].ToString(), out leaveID))
                        req.Id = leaveID;

                    leaveRequests.Add(req);
                }
            }

            return leaveRequests;
        }

        public List<IUser> GetAllManagerUsers()
        {
            List<IUser> managerUsers = new();
            string query = "SELECT * FROM user WHERE roles = '[\"ROLE_ADMIN\"]';";

            using (MySqlCommand command = new MySqlCommand(query, conn))
            using (MySqlDataReader reader = command.ExecuteReader())
            {
                while (reader.Read())
                {
                    IUser managerUser = new ManagerUser(
                        reader["firstName"].ToString(),
                        reader["lastName"].ToString(),
                        reader["email"].ToString(),
                        reader["password"].ToString(),
                        reader["roles"].ToString(),
                        int.Parse(reader["bsn"].ToString()),
                        DateTime.Parse(reader["startDate"].ToString())
                    );
                    managerUser.ID = int.Parse(reader["id"].ToString());
                    managerUsers.Add(managerUser);
                }
            }

            return managerUsers;
        }

        public List<DisplayUser> getAllUsers()
        {
            List<DisplayUser> users = new();
            string query = "SELECT * FROM `user`;";

            using (MySqlCommand command = new MySqlCommand(query, conn))
            using (MySqlDataReader reader = command.ExecuteReader())
            {
                while (reader.Read())
                {
                    DisplayUser user = new DisplayUser(
                        reader["firstName"].ToString(),
                        reader["lastName"].ToString(),
                        reader["email"].ToString(),
                        reader["password"].ToString(),
                        reader["roles"].ToString(),
                        int.Parse(reader["bsn"].ToString()),
                        DateTime.Parse(reader["startDate"].ToString()),
                        int.Parse(reader["leaveDaysPerYear"].ToString())
                    );
                    user.ID = int.Parse(reader["id"].ToString());
                    users.Add(user);
                }
            }
            return users;
        }

        public void SaveUserToDatabase(IUser newUser)
        {
            if (conn.State != System.Data.ConnectionState.Open)
                conn.Open();

            var cmd = new MySqlCommand($@"
        INSERT INTO {DatabaseEnums.DatabaseTables.user} 
        (firstName, lastName, email, password, roles, bsn, superVisor, startDate, leaveDaysPerYear, department) 
        VALUES 
        ('{MySqlHelper.EscapeString(newUser.FirstName)}', 
         '{MySqlHelper.EscapeString(newUser.LastName)}', 
         '{MySqlHelper.EscapeString(newUser.Email)}', 
         '{MySqlHelper.EscapeString(newUser.Password)}', 
         '{MySqlHelper.EscapeString(newUser.Position)}', 
         {newUser.Bsn}, 
         {newUser.SuperVisor}, 
         '{newUser.StartDate:yyyy-MM-dd}', 
         {newUser.LeaveDaysPerYear},
         '{MySqlHelper.EscapeString(newUser.Department)}'
        );
    ", conn);

            cmd.ExecuteNonQuery();
        }


        public virtual DisplayUser GetUserFromID(int userID)
        {
            if (conn.State != System.Data.ConnectionState.Open)
                conn.Open();

            string query = $"SELECT * FROM user WHERE id = '{userID}';";

            using (MySqlCommand command = new MySqlCommand(query, conn))
            using (MySqlDataReader reader = command.ExecuteReader())
            {
                while (reader.Read())
                {
                    

                    DisplayUser user = new DisplayUser(
                        reader["firstName"].ToString(),
                        reader["lastName"].ToString(),
                        reader["email"].ToString(),
                        reader["password"].ToString(),
                        reader["roles"].ToString(),
                        int.Parse(reader["bsn"].ToString()),
                        DateTime.Parse(reader["startDate"].ToString()),
                        int.Parse(reader["leaveDaysPerYear"].ToString())
                    );
                    user.Department = reader["department"].ToString();
                    return user;
                }
            }
            return null;
        }

        public virtual void ChangeLeaveStatus(int leaveID, LeaveEnums.LeaveStatus newStatus)
        {
            if (conn.State != System.Data.ConnectionState.Open)
                conn.Open();

            var cmd = new MySqlCommand($@"UPDATE `{DatabaseEnums.DatabaseTables.leave}` SET `status` = '{newStatus}' WHERE `id` = '{leaveID}';", conn);
            cmd.ExecuteNonQuery();
        }

        public void AddShift(int userID, DateOnly shiftDate, TimeOnly startTime, TimeOnly endTime)
        {
            if (conn.State != System.Data.ConnectionState.Open)
                conn.Open();

            var cmd = new MySqlCommand($@"
        INSERT INTO {DatabaseEnums.DatabaseTables.shifts} 
        (userID, startTime, endTime, shiftDate) 
        VALUES 
        ('{userID}',
         '{startTime}',
         '{endTime}',
         '{shiftDate:yyyy-MM-dd}'
        );
    ", conn);

            cmd.ExecuteNonQuery();
        }


        public ManagerUser GetManagerFromLoginCred(string email, string password)
        {
            if (conn.State != System.Data.ConnectionState.Open)
                conn.Open();

            string query = $"SELECT * FROM user WHERE email = '{MySqlHelper.EscapeString(email)}' AND password = '{MySqlHelper.EscapeString(password)}' AND roles = '[\"ROLE_ADMIN\"]';";

            using (MySqlCommand command = new MySqlCommand(query, conn))
            using (MySqlDataReader reader = command.ExecuteReader())
            {
                while (reader.Read())
                {
                    ManagerUser user= new ManagerUser(
                        reader["firstName"].ToString(),
                        reader["lastName"].ToString(),
                        reader["email"].ToString(),
                        reader["password"].ToString(),
                        reader["roles"].ToString(),
                        int.Parse(reader["bsn"].ToString()),
                        DateTime.Parse(reader["startDate"].ToString())
                    );
                    user.ID = int.Parse( reader["id"].ToString());
                    user.Department = reader["department"].ToString();
                    return user;
                }
            }
            return null;
        }

        public List<Department> GetDepartments()
        {
            if (conn.State != System.Data.ConnectionState.Open)
                conn.Open();

            string query = $"SELECT * FROM departments;";
            List<Department> departments = new List<Department>();

            using (MySqlCommand command = new MySqlCommand(query, conn))
            using (MySqlDataReader reader = command.ExecuteReader())
            {
                while (reader.Read())
                {
                    Department user = new Department(
                        int.Parse(reader["id"].ToString()),
                        reader["name"].ToString()
                    );

                    departments.Add(user);
                }
            }
            return departments;
        }
        public List<AuditItem> getAuditItems()
        {
            if (conn.State != System.Data.ConnectionState.Open)
                conn.Open();
            string query = $"SELECT * FROM audit_trail;";
            List<AuditItem> auditItems = new List<AuditItem>();
            using (MySqlCommand command = new MySqlCommand(query, conn))
            using (MySqlDataReader reader = command.ExecuteReader())
            {
                while (reader.Read())
                {
                    AuditItem auditItem = new AuditItem(
                        reader.GetInt32("id"),
                        reader.GetInt32("user_id"),
                        reader.GetString("action"),
                        reader.GetDateTime("date_time")
                    );
                    auditItems.Add(auditItem);
                }
            }
            return auditItems;
        }
        public void ShowAllShifts()
        {
            if (conn.State != System.Data.ConnectionState.Open)
                conn.Open();

            string query = $"SELECT * FROM `{DatabaseEnums.DatabaseTables.shifts}` ORDER BY shiftDate, startTime;";
            var shifts = new List<(int UserID, DateTime ShiftDateTime, TimeSpan Start, TimeSpan End)>();

            using (var cmd = new MySqlCommand(query, conn))
            using (var reader = cmd.ExecuteReader())
            {
                while (reader.Read())
                {
                    shifts.Add((
                        int.Parse(reader["userID"].ToString()),
                        reader.GetDateTime("shiftDate"),
                        reader.GetTimeSpan("startTime"),
                        reader.GetTimeSpan("endTime")
                    ));
                }
            }

            foreach (var shift in shifts)
            {
                DisplayUser user = GetUserFromID(shift.UserID);
                if (user == null || user.Department != SessionUser.sessionUser.Department)
                    continue;

                Console.WriteLine(
                    $"UserID: {shift.UserID} | " +
                    $"Name: {user.FirstName} | " +
                    $"Department: {user.Department} | " +
                    $"Date: {shift.ShiftDateTime:yyyy-MM-dd} | " +
                    $"From: {shift.Start} | " +
                    $"To: {shift.End}"
                );
            }
        }





    }
}
    