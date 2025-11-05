using MySqlConnector;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using static GeoProfs.UserManager;

namespace GeoProfs
{
     class EmployeeUser : IUser
    {
        public string FirstName { get; set; }
        public int ID { get; set; }
        public string LastName { get; set; }
        public string Email { get; set; }
        public string Password { get; set; }
        public string Position { get; set; }
        public int Bsn { get; set; }
        public int SuperVisor { get; set; }
        public DateTime StartDate { get; set; }
        public int LeaveDaysPerYear { get; set; } = 12;

        public EmployeeUser(string firstName, string lastName, string email, string password, string position, int bsn, DateTime startDate, int superVisor)
        {
            FirstName = firstName;
            LastName = lastName;
            Email = email;
            Password = password;
            Position = position;
            Bsn = bsn;
            SuperVisor = superVisor;
            StartDate = startDate;
            
        }
    }
    class ManagerUser : IUser {
        public string FirstName { get; set; }
        public int ID { get; set; }
        public string LastName { get; set; }
        public string Email { get; set; }
        public string Password { get; set; }
        public string Position { get; set; }
        public int Bsn { get; set; }
        public DateTime StartDate { get; set; }
        public int LeaveDaysPerYear { get; set; } = 12;
        public int SuperVisor { get; set; } = 0;
        public ManagerUser(string firstName, string lastName, string email, string password, string position, int bsn, DateTime startDate)
        {
            FirstName = firstName;
            LastName = lastName;
            Email = email;
            Password = password;
            Position = position;
            Bsn = bsn;
           
            StartDate = startDate;
        }
    }
    class CEOUser : IUser {
        public int ID { get; set; }
        public string FirstName { get; set; }
        public string LastName { get; set; }
        public string Email { get; set; }
        public string Password { get; set; }
        public string Position { get; set; }
        public int Bsn { get; set; }
        public DateTime StartDate { get; set; }
        public int LeaveDaysPerYear { get; set; } = 365;
        public int SuperVisor { get; set; } = 0;

        public CEOUser(string firstName, string lastName, string email, string password, string position, int bsn, DateTime startDate, int leaveDaysPerYear)
        {
            FirstName = firstName;
            LastName = lastName;
            Email = email;
            Password = password;
            Position = position;
            Bsn = bsn;
            StartDate = startDate;
            LeaveDaysPerYear = leaveDaysPerYear;
        }
    }
    class DisplayUser : IUser {
        public int ID { get; set; }
        public string FirstName { get; set; }
        public string LastName { get; set; }
        public string Email { get; set; }
        public string Password { get; set; }
        public string Position { get; set; }
        public int Bsn { get; set; }
        public DateTime StartDate { get; set; }
        public int LeaveDaysPerYear { get; set; } = 365;
        public int SuperVisor { get; set; } = 0;

        public DisplayUser(string firstName, string lastName, string email, string password, string position, int bsn, DateTime startDate, int leaveDaysPerYear)
        {
            FirstName = firstName;
            LastName = lastName;
            Email = email;
            Password = password;
            Position = position;
            Bsn = bsn;
            StartDate = startDate;
            LeaveDaysPerYear = leaveDaysPerYear;
        }


    }
}
