package com.example.klient;

import java.io.DataInputStream;
import java.io.DataOutputStream;
import java.io.IOException;
import java.net.Socket;
import java.net.UnknownHostException;

import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;
import com.example.mariusz.klient.R;

public class MainActivity extends AppCompatActivity {

    static final int SocketServerPORT = 8080;

    LinearLayout panelLogowania;
    LinearLayout panelChatu;

    EditText poleTekstoweUzytkownika;
    EditText poleTekstoweAdresu;

    TextView poleWiadomosc;
    TextView polePort;

    EditText wiadomoscWysylana;

    Button przyciskPolacz;
    Button przyciskWyslij;
    Button przyciskRozlacz;

    String logWiadomosc = "";

    WatekKlienta watekKlienta = null;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        panelLogowania = (LinearLayout) findViewById(R.id.loginpanel);
        panelChatu = (LinearLayout) findViewById(R.id.chatpanel);

        poleTekstoweUzytkownika = (EditText) findViewById(R.id.nazwaUzytkownika);
        poleTekstoweAdresu = (EditText) findViewById(R.id.adres);
        polePort = (TextView) findViewById(R.id.port);
        polePort.setText("   port: " + SocketServerPORT);
        przyciskPolacz = (Button) findViewById(R.id.polacz);
        przyciskRozlacz = (Button) findViewById(R.id.rozlacz);
        poleWiadomosc = (TextView) findViewById(R.id.tekstWiadomosc);

        wiadomoscWysylana = (EditText) findViewById(R.id.napiszWiadomosc);
        przyciskWyslij = (Button) findViewById(R.id.wyslij);

        przyciskPolacz.setOnClickListener(przyciskPolaczListener);
        przyciskWyslij.setOnClickListener(przyciskWyslijListener);
        przyciskRozlacz.setOnClickListener(przyciskRozlaczListener);
    }

    OnClickListener przyciskRozlaczListener = new OnClickListener() {

        @Override
        public void onClick(View v) {
            if (watekKlienta == null) {
                return;
            }
            watekKlienta.wyslijWiadomosc("rozlacz");
            watekKlienta.rozlacz();
        }
    };

    OnClickListener przyciskWyslijListener = new OnClickListener() {

        @Override
        public void onClick(View v) {
            if (!wiadomoscWysylana.getText().toString().equals("") && watekKlienta != null) {
                watekKlienta.wyslijWiadomosc(wiadomoscWysylana.getText().toString() + "\n");
                wiadomoscWysylana.setText("");
                wiadomoscWysylana.setHint("");
            }
        }
    };

    OnClickListener przyciskPolaczListener = new OnClickListener() {

        @Override
        public void onClick(View v) {
            String nazwaUzytkownika = poleTekstoweUzytkownika.getText().toString();
            if (nazwaUzytkownika.equals("")) {
                Toast.makeText(MainActivity.this, "Podaj nazwe użytkownika", Toast.LENGTH_LONG).show();
                return;
            }

            String adres = poleTekstoweAdresu.getText().toString();
            if (adres.equals("")) {
                Toast.makeText(MainActivity.this, "Podaj adres", Toast.LENGTH_LONG).show();
                return;
            }

            logWiadomosc = "";
            poleWiadomosc.setText(logWiadomosc);
            panelLogowania.setVisibility(View.GONE);
            panelChatu.setVisibility(View.VISIBLE);

            watekKlienta = new WatekKlienta(nazwaUzytkownika, adres, SocketServerPORT);
            watekKlienta.start();
        }

    };

    public class WatekKlienta extends Thread {

        String nazwa;
        String adresSerwera;
        int port;

        String wiadomoscDoWyslania = "";
        boolean zakonczDzialanie = false;

        WatekKlienta(String nazwa, String adres, int port) {
            this.nazwa = nazwa;
            adresSerwera = adres;
            this.port = port;
        }

        @Override
        public void run() {
            Socket socket = null;
            DataOutputStream dataOutputStream = null;
            DataInputStream dataInputStream = null;

            try {
                socket = new Socket(adresSerwera, port);

                dataOutputStream = new DataOutputStream(socket.getOutputStream());
                dataInputStream = new DataInputStream(socket.getInputStream());

                dataOutputStream.writeUTF(nazwa);
                dataOutputStream.flush();

                while (!zakonczDzialanie) {
                    if (dataInputStream.available() > 0) {
                        logWiadomosc += dataInputStream.readUTF();

                        MainActivity.this.runOnUiThread(new Runnable() {

                            @Override
                            public void run() {
                                poleWiadomosc.setText(logWiadomosc);
                            }
                        });
                    }

                    if (!wiadomoscDoWyslania.equals("")) {
                        dataOutputStream.writeUTF(wiadomoscDoWyslania);
                        dataOutputStream.flush();
                        wiadomoscDoWyslania = "";
                    }
                }

            } catch (UnknownHostException e) {
                e.printStackTrace();
                final String eString = e.toString();
                MainActivity.this.runOnUiThread(new Runnable() {

                    @Override
                    public void run() {
                        Toast.makeText(MainActivity.this, eString, Toast.LENGTH_LONG).show();
                    }

                });
            } catch (IOException e) {
                e.printStackTrace();
                final String eString = e.toString();
                MainActivity.this.runOnUiThread(new Runnable() {

                    @Override
                    public void run() {
                        Toast.makeText(MainActivity.this, eString, Toast.LENGTH_LONG).show();
                    }

                });
            } finally {
                if (socket != null) {
                    try {
                        socket.close();
                    } catch (IOException e) {
                        // TODO Auto-generated catch block
                        e.printStackTrace();
                    }
                }

                if (dataOutputStream != null) {
                    try {
                        dataOutputStream.close();
                    } catch (IOException e) {
                        // TODO Auto-generated catch block
                        e.printStackTrace();
                    }
                }

                if (dataInputStream != null) {
                    try {
                        dataInputStream.close();
                    } catch (IOException e) {
                        // TODO Auto-generated catch block
                        e.printStackTrace();
                    }
                }

                MainActivity.this.runOnUiThread(new Runnable() {

                    @Override
                    public void run() {
                        panelLogowania.setVisibility(View.VISIBLE);
                        panelChatu.setVisibility(View.GONE);
                    }

                });
            }

        }

        private void wyslijWiadomosc(String wiadomosc) {
            wiadomoscDoWyslania = wiadomosc;
        }

        private void rozlacz() {
            zakonczDzialanie = true;
        }
    }
}

