# -*- coding: utf-8 -*-

import csv
import datetime
import mysql.connector

from time import strftime


def export_sql_to_csv():
    conn = mysql.connector.connect(user="",
                                password="",
                                host="",
                                database="myDB")

    cursor = conn.cursor()
    query = ("SELECT UNIX_TIMESTAMP(rani.data), rani.id, ratownicy_wakcji.id, rani.opaska_id, rani.ratownik_id, rani.akcja_id, rani.szerokosc_geo, rani.dlugosc_geo, rani.tetno, rani.kolor, ratownicy_wakcji.czy_kam \
            FROM rani \
            INNER JOIN ratownicy_wakcji ON rani.ratownik_id = ratownicy_wakcji.ratownik_id \
            WHERE rani.nadawanie = false AND rani.aktywna_opaska = false AND rani.w_akcji = false AND ratownicy_wakcji.w_akcji = false \
            ORDER BY rani.data")
    cursor.execute(query)
    result = cursor.fetchall()

    date = strftime("%Y-%m-%d_%H-%M-%S")
    header = ["timestamp", "rani_id", "ratownicy_wakcji_id", "opaska_id", "ratownik_id", "akcja_id", "szerokosc_geo", "dlugosc_geo", "tetno", "kolor", "czy_kam"]

    with open("triaz_" + date + ".csv", "w", newline='') as csv_file:
        csv_writer = csv.writer(csv_file)
        csv_writer.writerow(header)
        for row in result:
            csv_writer.writerow(row)

    conn.close()


if __name__ == '__main__':
    export_sql_to_csv()
    