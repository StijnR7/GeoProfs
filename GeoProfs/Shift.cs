using GeoProfs.Enums;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace GeoProfs
{
    public class Shift
    {
        public int Id { get; set; }
        public int UserID { get; set; }
        public TimeOnly StartTime { get; set; }
        public TimeOnly endTime { get; set; }
        public DateOnly ShiftDate { get; set; }
        public UserEnums.UserPositions Position { get; set; } = UserEnums.UserPositions.employee;
      
    }
}
