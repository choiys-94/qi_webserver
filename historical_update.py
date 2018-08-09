#coding: utf-8
import requests
import sys
import time
import random
import json

today = str(time.time()).split('.')[0]
url = "http://teamc-iot.calit2.net/api/sensor/historical/insert"
token = "7d6992bcd157b063c4960b842f89b463"
for i in range(90):
	print "{} try".format(today)
	heart = random.randrange(40, 181)
	o3 = random.randrange(125, 605)
	so2 = random.randrange(0, 1005)
	no2 = random.randrange(0, 2050)
	co = random.randrange(0, 10001)
	pm25 = random.randrange(0, 501)
	temp = random.randrange(10, 45)
	json_data = {
		'o3': o3,
		'so2': so2,
		'no2': no2,
		'co': co,
		'pm25': pm25,
		'temp': temp,
		'heart': heart,
		'timestamp': today,
		'token': token
	}
	json_data = json.dumps(json_data)
	data = {'json': json_data}
	r = requests.post(url, data=data)
	today = str(int(today) - 86400)
