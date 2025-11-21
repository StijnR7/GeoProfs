using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace GeoProfs
{
    public static class AppSettings
    {
        public static int employeesWorkingMorning { get; set; } = 1;
        public static int employeesWorkingAfternoon { get; set; } = 2;
        public static int employeesWorkingEvening { get; set; } = 1;

        public static void AdjustSettings() {
            Console.WriteLine("[0] Adjust morning employees");
            Console.WriteLine("[1] Adjust afternoon employees");
            Console.WriteLine("[2] Adjust evening employees");

            int choice;
            bool success1 = false;
            bool success = int.TryParse(Console.ReadLine(), out choice);
            if (!success || choice < 0 || choice > 2) { 
                Console.WriteLine("Invalid choice.");
                return;
            }
            int newNeeded;
            switch (choice) {
                case 0:
                    Console.WriteLine("How many employees needed in the morning?");
                    success1 = int.TryParse(Console.ReadLine(), out newNeeded);
                    if (success1) { employeesWorkingMorning = newNeeded; }
                    break;
                case 1:
                    Console.WriteLine("How many employees needed in the afternoon?");
                     success1 = int.TryParse(Console.ReadLine(), out newNeeded);
                    if (success1) { employeesWorkingAfternoon = newNeeded; }
                    break;
                 case 2:
                    Console.WriteLine("How many employees needed in the evening?");
                     success1 = int.TryParse(Console.ReadLine(), out newNeeded);
                    if (success1) { employeesWorkingEvening = newNeeded; }
                    break;
            
            
            }
            if (success1)
            {
                Console.WriteLine("Successfully updated");
            }
            else { 
                Console.WriteLine("Updating settings failed. Make sure the input is a valid number.");
            
            }



        }
    }
}
