def add_time(par1, par2, day=""):
	
	week = ("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday")
	
	tim = list(par1.split(" "))
	time = tim[0].split(":")
	duration = list(par2.split(":"))
	
	z = [int (i) for i in duration]
	s = [int(i) for i in time]
	suma = [x + y for (x, y) in zip(s, z)]
	
	timeZone = tim[1]
	horas = int(suma[0])
	minutos = int(suma[1])
	
	if minutos >= 60:	#Add minutes
		horas += 1
		minutos %= 60
	if minutos <= 9:
		minutos = "0" + str(minutos)
		
	period = 0
	if tim[1] == "PM":   #Counting the days
		horas += 12
	if horas > 24:
		period = horas // 24

	horas %= 24
	
	if horas > 0 and horas < 12:	#Calculate if it's AM or PM
		timeZone = "AM"
	elif horas >= 12:
		timeZone = "PM"
	
	if horas > 12:
		horas %= 12

	if horas == 0:
		horas = "12"
		timeZone = "AM"
	
	if period:		# Calculating how many days are going on
		if period == 1:
			days_pased = " (next day)"
		else:
			days_pased = " (" + str(period) + " days later)"
	else:
		days_pased = ""
	
	if day:				# Calculating the days of the week
		number = week.index(day.lower().capitalize()) + period
		if number > 7:
			number %= 7
			weekDay = ", " + week[number]
		elif number <= 7:
			weekDay = ", " + week[number]
	else:
		weekDay = ""
	
	finalDate =  str(horas) + ":" + str(minutos) + " " + timeZone + weekDay + days_pased

	return finalDate
