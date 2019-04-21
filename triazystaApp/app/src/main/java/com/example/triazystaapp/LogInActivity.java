package com.example.triazystaapp;

import android.content.Context;
import android.content.Intent;
import android.content.pm.ActivityInfo;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;

public class LogInActivity extends AppCompatActivity {

    EditText username;
    Button login;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_log_in);
        checkNetworkConnection();
        username = findViewById(R.id.username);
        login = findViewById(R.id.login);
        setRequestedOrientation(ActivityInfo.SCREEN_ORIENTATION_PORTRAIT);
        login.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                loginUser();
            }
        });
    }

    private void loginUser() {
        RequestQueue requestQueue = Volley.newRequestQueue(this);
        final String id = username.getText().toString().trim();
        final String url = "url";

        StringRequest stringRequest = new StringRequest(Request.Method.POST, url,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        Toast.makeText(LogInActivity.this, response, Toast.LENGTH_LONG).show();
                        checkAccessPermission(response);
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Toast.makeText(LogInActivity.this, error.toString(), Toast.LENGTH_LONG).show();
                    }
                }) {
            @Override
            public byte[] getBody() throws AuthFailureError {
                HashMap<String, String> params = new HashMap<String, String>();
                params.put("secret", "secret");
                params.put("mode", "verify-login");
                params.put("resc_id", id);
                return new JSONObject(params).toString().getBytes();
            }

            @Override
            public String getBodyContentType() {
                return "application/json";
            }
        };
        requestQueue.add(stringRequest);
    }

    public void checkAccessPermission(String response) {

        try {
            JSONObject jsonObject = new JSONObject(response);
            if (jsonObject.getString("status").equals("success")) {
                Intent intent = new Intent(LogInActivity.this, MapsActivity.class);
                startActivity(intent);
            }
        } catch (JSONException e) {
            e.printStackTrace();
        }

    }

    private boolean checkNetworkConnection() {
        ConnectivityManager connMgr = (ConnectivityManager)
                getSystemService(Context.CONNECTIVITY_SERVICE);
        NetworkInfo networkInfo = connMgr.getActiveNetworkInfo();
        boolean isConnected = false;
        if (networkInfo != null && (isConnected = networkInfo.isConnected())) {
            Log.i("Network", "Ok");
        } else {
            Toast.makeText(LogInActivity.this, "Turn on the internet connection", Toast.LENGTH_SHORT).show();
            Log.i("Network", "No connection");
        }
        return isConnected;
    }
}
