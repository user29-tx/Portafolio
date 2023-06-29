class Category:
	def __init__(self, name):
		self.n = name
		self.ledger = []

	def __str__(self):
		spend = self.n.center(30, "*") + "\n"
		for x in self.ledger:
			spend += f"{x['description'][:23]}".ljust(23) + f"{x['amount']:7.2f}" + "\n"
		spend += "Total: " + str(self.get_balance())
		return spend
		
	def deposit(self, amount, description = ""):
		self.ledger.append({"amount": amount, "description": description})
	
	def withdraw(self, amount, description = ""):
		if self.check_funds(amount):
			self.ledger.append({"amount": -amount, "description": description})
			return True
		return False
		
	def get_balance(self):
		balance = 0
		for x in self.ledger:
			balance += x['amount']
		return balance
		
	def transfer(self, amount, description2):
		if self.check_funds(amount):
			self.withdraw(amount, "Transfer to " + description2.n)
			description2.deposit(amount, "Transfer from " + self.n)
			return True
		return False

	def check_funds(self, amount):
		if amount > self.get_balance():
			return False
		return True

def create_spend_chart(categories):
	withdraw = []
	for category in categories:
		total = 0
		for item in category.ledger:
			if item["amount"] < 0:
				total += item["amount"]
		withdraw.append(total)
	
	maxNumber = sum(withdraw)
	percentage = [i/maxNumber * 100 for i in withdraw]
	
	numbers = "Percentage spent by category"
	for i in range(100, -1, -10):
		numbers += "\n" + str(i).rjust(3) + "|"
		for o in percentage:
			if o > i:
				numbers += " o "
			else:
				numbers += "   "
		numbers += " "
	numbers += "\n    " + "-"*10

	length = []
	
	for category in categories: #This is to pick the biggest word
		length.append(len(category.n))
	maxN = max(length)

	for i in range(maxN): #This is to print the words
		numbers += "\n    "
		for j in range(len(categories)):
			if i < length[j]:
				numbers += " " + categories[j].n[i] + " "
			else:
				numbers += "   "
		numbers += " "
	return numbers
