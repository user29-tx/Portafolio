import random
import copy

class Hat:
	def __init__(self, **kwargs):
		self.contents = []
		for name, value in kwargs.items():	
			for x in range(value):
				self.contents.append(name)
	
	def draw(self, number):
		if number > len(self.contents):
			return self.contents
		
		draws = []
		for x in range(number):
			rando = random.randrange(len(self.contents))
			draws.append(self.contents.pop(rando))
		return draws

def experiment(hat, expected_balls, num_balls_drawn, num_experiments):
	
	expected = []
	for x in expected_balls:
		expected.append(expected_balls[x])
	
	success = 0
	
	for x in range(num_experiments):
		hat2 = copy.deepcopy(hat)
		balls = hat2.draw(num_balls_drawn)
		
		numberBalls = []
		
		for key in expected_balls:
			numberBalls.append(balls.count(key))
		
		if numberBalls >= expected:
			success += 1
	return success / num_experiments
		
