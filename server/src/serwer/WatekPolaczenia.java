package serwer;

import java.io.DataInputStream;
import java.io.DataOutputStream;
import java.io.IOException;
import java.net.Socket;

public class WatekPolaczenia extends Thread
{
    private Serwer serwer;
    private Socket socket;
    private UzytkownikChatu uzytkownik;
    private String wiadomoscDoWyslania = "";

    WatekPolaczenia(Serwer serwer, UzytkownikChatu uzytkownik, Socket socket)
    {
        this.serwer = serwer;
        this.uzytkownik = uzytkownik;
        this.socket = socket;
        this.uzytkownik.socket = socket;
        this.uzytkownik.watekPolaczenia = this;
    }

    @Override
    public void run()
    {
        DataInputStream dataInputStream = null;
        DataOutputStream dataOutputStream = null;

        try
        {
            dataInputStream = new DataInputStream(socket.getInputStream());
            dataOutputStream = new DataOutputStream(socket.getOutputStream());

            String nazwa = dataInputStream.readUTF();

            uzytkownik.nazwa = nazwa;

            serwer.logWiadomosci = uzytkownik.nazwa + " polaczony. Adres IP: " + uzytkownik.socket.getInetAddress() + " port: " + uzytkownik.socket.getPort() + "\n";

            System.out.println(serwer.logWiadomosci);

            dataOutputStream.writeUTF("  Witaj " + nazwa + "!\n");
            dataOutputStream.flush();


            serwer.logWiadomosci = nazwa + " dolaczyl‚ do chatu.\n";
            System.out.print(serwer.logWiadomosci);

            serwer.wyslijDoWszystkich(nazwa + " dolaczyl‚ do chatu.\n");

            String nowaWiadomosc = "";

            while (!nowaWiadomosc.equals("rozlacz"))
            {
                if (dataInputStream.available() > 0)
                {
                    nowaWiadomosc = dataInputStream.readUTF();

                    if (!nowaWiadomosc.equals("rozlacz"))
                    {
                        serwer.logWiadomosci = nazwa + ": " + nowaWiadomosc;
                        System.out.print(serwer.logWiadomosci);

                        serwer.wyslijDoWszystkich(nazwa + ": " + nowaWiadomosc);
                    }
                }

                if (!wiadomoscDoWyslania.equals("") && !nowaWiadomosc.equals("rozlacz"))
                {
                    dataOutputStream.writeUTF(wiadomoscDoWyslania);
                    dataOutputStream.flush();
                    wiadomoscDoWyslania = "";
                }
            }
        }
        catch (IOException e)
        {
            System.out.println("Cos poszlo nie tak! " + e.toString() + "\n");
        }
        finally
        {
            if (dataInputStream != null)
            {
                try
                {
                    dataInputStream.close();
                }
                catch (IOException e)
                {
                    System.out.println("cos poszlo‚o nie tak! " + e.toString() + "\n");
                }
            }

            if (dataOutputStream != null)
            {
                try
                {
                    dataOutputStream.close();
                }
                catch (IOException e)
                {
                    System.out.println("cos poszlo nie tak! " + e.toString() + "\n");
                }
            }

            serwer.listaUzytkownikow.remove(uzytkownik);

            System.out.println(uzytkownik.nazwa + " usunity z listy uzytkownikow\n");

            serwer.logWiadomosci = uzytkownik.nazwa + " opuscil‚ chat";
            System.out.println(serwer.logWiadomosci);

            serwer.wyslijDoWszystkich(uzytkownik.nazwa + " OPUSCIL\n");
        }
    }

    public void wyslijWiadomosc(String wiadomosc)
    {
        wiadomoscDoWyslania = wiadomosc;
    }
}
