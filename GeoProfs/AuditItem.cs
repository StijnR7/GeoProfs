using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace GeoProfs
{
    public class AuditItem
    {
        public AuditItem(int id, int user_id, string action, DateTime date_time)
        {
            this.id = id;
            this.user_id = user_id;
            this.action = action;
            this.date_time = date_time;
        }

        public int id { get; set; }
        public int user_id { get; set; }
        public string action { get; set; }
        public DateTime date_time { get; set; }


        public DisplayUser getUserObject(Database database) {
            return database.GetUserFromID(user_id);
        
        }

    }
}
