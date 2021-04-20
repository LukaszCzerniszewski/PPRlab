using System;
using System.Net;
using System.Net.Sockets;
using System.Text;
using System.Diagnostics;
using System.ComponentModel;

namespace socket
{
    class Program
    {
        static void Main(string[] args)
        {
			byte[] bytes = new byte[1024];
			try {
			
				IPHostEntry ipHostInfo = Dns.GetHostEntry("127.1.0.0");
				IPAddress ipAddress = ipHostInfo.AddressList[0];
				IPEndPoint remoteEP = new IPEndPoint(ipAddress,12345);
				Socket sender = new Socket(ipAddress.AddressFamily, SocketType.Dgram, ProtocolType.Udp);
				try {
					
					sender.Connect(remoteEP);  

					Console.WriteLine($"Socket connected to {sender.RemoteEndPoint.ToString()}");
					Console.WriteLine($"Sent = ");
					
					string message = Console.ReadLine();
					int ID = Process.GetCurrentProcess().Id;
					message = ID.ToString() + message;

					byte[] msg = Encoding.Unicode.GetBytes(message);
					sender.Send(msg);
					sender.Receive(bytes);
					Console.WriteLine(message);
					sender.Shutdown(SocketShutdown.Both);  
					sender.Close();
				} catch (Exception e) {
					Console.WriteLine($"Unexpected exception : {e.ToString()}");
					Console.WriteLine(remoteEP);
					

				}
			} catch (Exception e) {
				Console.WriteLine( e.ToString());
			}
        }
		
    }
	

}
