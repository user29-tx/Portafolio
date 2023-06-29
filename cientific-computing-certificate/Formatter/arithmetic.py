import re

def arithmetic_arranger(problems, result = False):
	if len(problems) > 5:
		return "Error: Too many problems"
	
	first = ""
	second = ""
	line = ""
	third = ""
	
	for x in problems:
		
		a = x.split()[0]
		b = x.split()[1]
		c = x.split()[2]
		
		if len(a) > 5 or len(c) > 5:
			return "Error: Numbers cannot be more than four digits"
					
		if b == "+":
			suma = int(a) + int(c)
		elif b == "-":
			suma = int(a) - int(c)
		else:
			return "Error: Operator must be '+' or '-'."
		
		if(re.search('[^\s0-9.+-]', x)):
			return "Error: Numbers must only contain digits"
			
		spaces = max(len(a), len(c))+2
		top = a.rjust(spaces)
		middle = b + c.rjust(spaces -1)
		down = str(suma).rjust(spaces)
		
		if x != problems:
			first += top + ' '*4
			second += middle + '    '
			line += spaces*'-' + '    '
			third += down + '    '

	s = first + "\n" + second + "\n" + line + "\n" + third
	z = first + "\n" + second + "\n" + line
	
	if result:
		return s
	else:
		return z
