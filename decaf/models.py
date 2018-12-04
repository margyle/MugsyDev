from extensions import mysql


class requestBrewSettings:
	def get(a):
		user = a		
		cur = mysql.connect().cursor()
		cur.execute("select * from mugsy.brewSettings WHERE userID=(%s)",user)
		r = [dict((cur.description[i][0], value)
			for i, value in enumerate(row)) for row in cur.fetchall()]
		return {'brewSettings' : r}

class getCoffeeInfo:
	def get(a):
		coffeeTypeId = a		
		cur = mysql.connect().cursor()
		cur.execute("select * from mugsy.coffeeTypes WHERE coffeeTypeId=(%s)",coffeeTypeId)
		r = [dict((cur.description[i][0], value)
			for i, value in enumerate(row)) for row in cur.fetchall()]
		return {'brewSettings' : r}




