package serwer;

import java.io.IOException;
import java.net.InetAddress;
import java.net.NetworkInterface;
import java.net.ServerSocket;
import java.net.Socket;
import java.net.SocketException;
import java.util.ArrayList;
import java.util.Enumeration;
import java.util.List;

public class Serwer
{
    static int PORT = 8080;

    String logWiadomosci = "";

    List<UzytkownikChatu> listaUzytkownikow;

    ServerSocket serverSocket;

    Serwer()
    {
        System.out.print(znajdzAdresIp());
        listaUzytkownikow = new ArrayList<>();
        WatekSerwera watekSerwera = new WatekSerwera();
        watekSerwera.start();
    }

    public class WatekSerwera extends Thread
    {
        @Override
        public void run()
        {
            Socket socket = null;
            try
            {
                serverSocket = new ServerSocket(PORT);
                System.out.println("Port serwera: " + serverSocket.getLocalPort() + "\n");

                while (true)
                {
                    socket = serverSocket.accept();
                    UzytkownikChatu uzytkownik = new UzytkownikChatu();
                    listaUzytkownikow.add(uzytkownik);
                    WatekPolaczenia watekPolaczenia = new WatekPolaczenia(Serwer.this, uzytkownik, socket);
                    watekPolaczenia.start();
                }

            }
            catch (IOException e)
            {
                System.out.println("Cos poszlo nie tak! " + e.toString() + "\n");
            }
            finally
            {
                if (socket != null)
                {
                    try
                    {
                        socket.close();
                    }
                    catch (IOException e)
                    {
                        System.out.println("Cos poszlo nie tak! " + e.toString() + "\n");
                    }
                }
            }
        }
    }

    public void wyslijDoWszystkich(String wiadomosc)
    {

        for (int i = 0; i < listaUzytkownikow.size(); i++)
        {
            listaUzytkownikow.get(i).watekPolaczenia.wyslijWiadomosc("  " + wiadomosc);
            logWiadomosci = "     wyslane do: " + listaUzytkownikow.get(i).nazwa + "\n";
            System.out.print(logWiadomosci);
        }
        System.out.println();
    }

    public String znajdzAdresIp()
    {
        String ip = "";
        try
        {
            Enumeration<NetworkInterface> enumNetworkInterfaces = NetworkInterface.getNetworkInterfaces();
            while (enumNetworkInterfaces.hasMoreElements())
            {
                NetworkInterface networkInterface = enumNetworkInterfaces.nextElement();
                Enumeration<InetAddress> enumInetAddress = networkInterface.getInetAddresses();
                while (enumInetAddress.hasMoreElements())
                {
                    InetAddress inetAddress = enumInetAddress.nextElement();

                    if (inetAddress.isSiteLocalAddress())
                    {
                        ip += "Adres IP serwera: " + inetAddress.getHostAddress() + "\n";
                    }
                }
            }
        }
        catch (SocketException e)
        {
            ip += "Cos poszlo nie tak! " + e.toString() + "\n";
        }
        return ip;
    }
}