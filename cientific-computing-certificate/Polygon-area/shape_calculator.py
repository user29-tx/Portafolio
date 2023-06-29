class Rectangle:
	def __init__(self, width, height):
		self.w = width
		self.h = height
	
	def __str__(self):
		return "Rectangle(width=" + str(self.w) + ", height=" + str(self.h) + ")"
	
	def set_width(self, width):
		self.w = width
	
	def set_height(self, height):
		self.h = height
	
	def get_area(self):
		return self.w * self.h
	
	def get_perimeter(self):
		return 2 * (self.w + self.h)

	def get_diagonal(self):
		return (self.w ** 2 + self.h ** 2) ** .5
	
	def get_picture(self):
		if self.w > 50 or self.h > 50:
			return "Too big for picture."
		return ("*"*self.w + "\n") * self.h
		
	def get_amount_inside(self, shape):
		return self.get_area() // shape.get_area()

class Square(Rectangle):
	def __init__(self, side):
		self.w = side
		self.h = side
	
	def __str__(self):
		return "Square(side=" + str(self.w) + ")"

	def set_side(self, side):
		self.w = side
		self.h = side
