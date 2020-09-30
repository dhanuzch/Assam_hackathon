import json 
import mysql.connector

mydb = mysql.connector.connect(
  host="127.0.0.1",
  user="root",
  password="",
  database="assam_police_hackathon"
)

mycursor = mydb.cursor()
mycursor2 = mydb.cursor()


List = []
with open('JSON.txt') as f:
    data = json.loads("[" + 
    f.read().replace("}\n{", "},\n{") + 
    "]")
    List.append(data)
    

    numberplate=(data[0]["results"][0]["plate"])
    print(numberplate)

sql = "DELETE FROM 2_entry_exit_control WHERE current_count=(%s)"
val = (numberplate,)
mycursor.execute(sql, val)
mydb.commit()   

sql2 = "DELETE FROM 2_entryexit_with_details WHERE registration_no=(%s)"
mycursor2.execute(sql2, val)
mydb.commit() 


    



