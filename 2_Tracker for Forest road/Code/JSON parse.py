import json 
import mysql.connector

mydb = mysql.connector.connect(
  host="127.0.0.1",
  user="root",
  password="",
  database="assam_police_hackathon"
)

mycursor = mydb.cursor()

List = []
with open('t.txt') as f:
    data = json.loads("[" + 
    f.read().replace("}\n{", "},\n{") + 
    "]")
    List.append(data)
    

    numberplate=(data[0]["results"][0]["plate"])
    print(numberplate)

sql = "INSERT INTO 2_entry_exit_control (id ,registration_no) VALUES (%s, %s)"
val = (1, numberplate)
mycursor.execute(sql, val)

mydb.commit()    

#with open('x.txt') as json_file:
#    y = json.load(json_file)
#    for i in range(0,93):
#       print(y["results"][0]["plate"])

    



