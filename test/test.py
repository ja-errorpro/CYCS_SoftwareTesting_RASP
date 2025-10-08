import requests


URL = "http://127.0.0.1:8080/"

payload = ''
with open("TC-RASP-NORMAL-004.txt", "r") as f:
    payload = f.read().strip()

def test_rasp():
    response = requests.post(URL, data={"ip": payload})
    if response.status_code == 200:
      print("Passed")
    else:
      print(response)
    
if __name__ == "__main__":
    test_rasp()
