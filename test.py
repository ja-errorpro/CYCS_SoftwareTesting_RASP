import requests
from termcolor import colored

URL = "http://127.0.0.1:8080/"

TEST_DIR = "test"

# read TC-RASP-ATTACK-001~010.txt files

attack_tests = []
for i in range(1, 11):
    with open(f"{TEST_DIR}/TC-RASP-ATTACK-{i:03}.txt", "r") as f:
        attack_tests.append(f.read().strip())

def test_rasp():

    # Test attack cases
    for i, ip in enumerate(attack_tests):
        response = requests.post(URL, data={"ip": ip})
        # the response must contain 'RASP Detected and Blocked a Command Injection Attempt!'
        if response.status_code == 200 and "RASP Detected and Blocked a Command Injection Attempt!" in response.text:
            print(colored(f"Attack Test {i+1}: Passed", "green"))
        else:
            print(colored(f"Attack Test {i+1}: Failed (Status Code: {response.status_code})", "red"))

if __name__ == "__main__":
    test_rasp()
