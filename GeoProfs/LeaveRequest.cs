using GeoProfs.Enums;
using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Text;
    using System.Threading.Tasks;

    namespace GeoProfs
    {
        public class LeaveRequest
        {
        
            public int Id { get; set; }
            public int UserId { get; set; }
            public DateTime StartDate { get; set; }
            public DateTime EndDate { get; set; }
            public LeaveEnums.LeaveStatus Status { get; set; } = LeaveEnums.LeaveStatus.pending;

            public LeaveRequest(int UserId, DateTime StartDate, DateTime EndDate) { 
                this.UserId = UserId;
                this.StartDate = StartDate;
                this.EndDate = EndDate;

        
            }
        }
    }
