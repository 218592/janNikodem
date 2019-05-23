package com.example.wristbandsim

import android.support.v7.app.AppCompatActivity
import android.os.Bundle
import android.view.View
import android.widget.*
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import org.jetbrains.anko.activityUiThread
import org.jetbrains.anko.doAsync
import org.json.JSONArray
import org.json.JSONObject
import java.util.*


class MainActivity : AppCompatActivity() {

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)

        val startButton = findViewById<Button>(R.id.start)
        val stopButton = findViewById<Button>(R.id.stop)
        val bandIdView = findViewById<TextView>(R.id.band_id)
        //val idView = findViewById<TextView>(R.id.id)
        val pulseView = findViewById<TextView>(R.id.pulse)

        val spinner = findViewById<Spinner>(R.id.band_selector)


        var bandId = "1"

        bandIdView.text = "Wybrana opaska: $bandId"

        ArrayAdapter.createFromResource(
            this,
            R.array.band_ids,
            android.R.layout.simple_spinner_item
        ).also { adapter ->
            // Specify the layout to use when the list of choices appears
            adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item)
            // Apply the adapter to the spinner
            spinner.adapter = adapter
        }

        spinner?.onItemSelectedListener = object : AdapterView.OnItemSelectedListener{
            override fun onNothingSelected(parent: AdapterView<*>?) {

            }

            override fun onItemSelected(parent: AdapterView<*>?, view: View?, position: Int, idS: Long) {
                bandId = parent?.getItemAtPosition(position).toString()
                bandIdView.text = "Wybrana opaska: $bandId"
            }

        }

        val queue = Volley.newRequestQueue(this)
        val url = "http://:8080/api.php"
        val secret = ""

        val random = Random()

        var threadRunning: Boolean

        var id = ""

        stopButton.isEnabled = false


        startButton.setOnClickListener {
            val data = JSONObject()
            data.put("secret", secret)
            data.put("mode", "enable-band")
            data.put("band_id", bandId)

            val request = object: StringRequest(Method.POST, url,
                Response.Listener { response ->
                    try {
                        val jsonObject = JSONArray(response).getJSONObject(1).getJSONObject("data");
                        id = jsonObject.getString("id")
                        //idView.text = "ID: $id"
                    } catch (e: Exception) {
                        //val jsonObject = JSONObject(response);
                        //val err = jsonObject.getString("error")
                        //idView.text = "Error: $err"
                        Toast.makeText(this, "Błąd danych", Toast.LENGTH_SHORT).show()
                    }
                },
                Response.ErrorListener {
                    Toast.makeText(this, "Błąd", Toast.LENGTH_SHORT).show()
                    //error -> Toast.makeText(this, error.toString(), Toast.LENGTH_SHORT).show()
                }) {
                override fun getBodyContentType(): String {
                    return "application/json"
                }

                override fun getBody(): ByteArray {
                    return data.toString().toByteArray()
                }
            }

            queue.add(request)
            Thread.sleep(1000)

            startButton.isEnabled = false
            spinner.isEnabled = false
            stopButton.isEnabled = true

            Toast.makeText(this, "Uruchomiono", Toast.LENGTH_SHORT).show()
            threadRunning = true

            doAsync {
                while (threadRunning) {
                    val pulse = (random.nextInt(80 - 65) + 65).toString()
                    val update = JSONObject()
                    update.put("secret", secret)
                    update.put("mode", "update-band")
                    update.put("id", id)
                    update.put("pulse", pulse)

                    activityUiThread {
                        pulseView.text = "Tętno: $pulse"
                    }
                    //textView.text = "Update: %s".format(update.toString())

                    val updateReq = object : StringRequest(Method.POST, url,
                        Response.Listener { //updateResponse ->
                            //textView.text = "Response: %s".format(updateResponse.toString())
                        },
                        Response.ErrorListener {
                            // Error
                        }) {
                        override fun getBodyContentType(): String {
                            return "application/json"
                        }

                        override fun getBody(): ByteArray {
                            return update.toString().toByteArray()
                        }
                    }

                    queue.add(updateReq)
                    Thread.sleep(1000)
                }
            }
        }

        stopButton.setOnClickListener {
            threadRunning = false
            val data = JSONObject()
            data.put("secret", secret)
            data.put("mode", "disable-band")
            data.put("id", id)

            //textView.text = "Data: %s".format(data.toString())

            val request = object : StringRequest(Method.POST, url,
                Response.Listener { //response ->

                },
                Response.ErrorListener {
                    Toast.makeText(this, "Błąd", Toast.LENGTH_SHORT).show()
                }) {
                override fun getBodyContentType(): String {
                    return "application/json"
                }

                override fun getBody(): ByteArray {
                    return data.toString().toByteArray()
                }
            }

            queue.add(request)
            Thread.sleep(1000)

            startButton.isEnabled = true
            spinner.isEnabled = true
            stopButton.isEnabled = false

            Toast.makeText(this, "Zatrzymano", Toast.LENGTH_SHORT).show()
        }
    }
}
