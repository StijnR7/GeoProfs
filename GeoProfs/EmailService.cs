using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Net;
using System.Net.Mail;
using System.Net.Mime;
using GeoProfs.Enums;
namespace GeoProfs
{
    public class EmailService
    {
        string smtpAddress = "smtp.gmail.com";
        int portNumber = 587;
        bool enableSSL = true;

        string emailFrom = "geoprofsupdates@gmail.com";
        string password = "elnl qsxx rauk ofpb";
        string subject = "Hello";
        string body = "This is a test email";

        public virtual void SendLeaveStatusUpdateEmail(string emailTo, LeaveEnums.LeaveStatus updatedStatus, LeaveRequest leaveRequest) {
            using (MailMessage mail = new MailMessage())
            {
                body = $"The status of your leave request from:\n{leaveRequest.StartDate} till {leaveRequest.EndDate} \nhas been {updatedStatus}";
                subject = $"Leave request {updatedStatus}";
                mail.From = new MailAddress(emailFrom);
                mail.To.Add(emailTo);
                mail.Subject = subject;
                mail.Body = body;
                mail.IsBodyHtml = false;

                using (SmtpClient smtp = new SmtpClient(smtpAddress, portNumber))
                {
                    smtp.Credentials = new NetworkCredential(emailFrom, password);
                    smtp.EnableSsl = enableSSL;
                    smtp.Send(mail);
                }
            }

            Console.WriteLine("Email sent successfully!");

        }
    }
}
