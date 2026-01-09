    using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using GeoProfs.Enums;
namespace GeoProfs
{
    public interface IUser
    {
        public string FirstName { get; set; }
        public string LastName { get; set; }
        public string Email { get; set; }
        public string Password { get; set; }
        public string Position { get; set; }
        public int Bsn { get; set; }
        public DateTime StartDate { get; set; }
        public int LeaveDaysPerYear { get; set; }
        public int ID { get; set; }
        public int SuperVisor {  get; set; } 
        public string Department { get; set; }


    }
}
