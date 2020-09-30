import json 
import mysql.connector

mydb = mysql.connector.connect(
  host="127.0.0.1",
  user="root",
  password="",
  database="assam_police_hackathon"
)

mycursor = mydb.cursor()
mycursor1 = mydb.cursor()
mycursor2 = mydb.cursor()
mycursor3 = mydb.cursor()
mycursor4 = mydb.cursor()

List = []
with open('JSON.txt') as f:
    data = json.loads("[" + 
    f.read().replace("}\n{", "},\n{") + 
    "]")
    List.append(data)
    

    numberplate=(data[0]["results"][0]["plate"])
    print(numberplate)

sql = "INSERT INTO 2_entry_exit_control (entry_count, current_count) VALUES (%s, %s)"
val = (numberplate,numberplate)
mycursor.execute(sql, val)
mydb.commit()  

sql1 = "SELECT registration_no, brand, model, year, Colour FROM vehicle_db WHERE registration_no LIKE (%s)"
val1 = (numberplate,)
mycursor1.execute(sql1, val1)
myresult1 = mycursor1.fetchall()

for x in myresult1:
  print(x)

sql3 = "SELECT EXISTS(SELECT * from prohibited_and_stolen_vehicles WHERE registration_no=(%s))"
val3 = (numberplate,)
mycursor3.execute(sql3, val3)
myresult2 = mycursor3.fetchone()

for y in myresult2:
  print(y)
  
if  myresult2[0] == 1:
    sql4 = "INSERT INTO 2_entryexit_stolen (registration_no, brand, model, year, Colour) VALUES (%s, %s, %s, %s, %s)"
    mycursor4.execute(sql4, myresult1[0])
    mydb.commit() 
elif myresult2[0] == 0:
    sql2 = "INSERT INTO 2_entryexit_with_details (registration_no, brand, model, year, Colour) VALUES (%s, %s, %s, %s, %s)"
    mycursor2.execute(sql2, myresult1[0])
    mydb.commit() 


