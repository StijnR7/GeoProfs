using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace GeoProfs
{
    public class Department
    {
        public string Name { get; set; }
        public int Id { get; set; }
        public Department(int id, string name)
        {
            Id = id;
            Name = name;

        }
    }
}
