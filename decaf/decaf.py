from getBrewSettings import requestBrewSettings, getCoffeeInfo
from flask import Flask, request, jsonify, make_response
from flask_restful import Resource, Api
from flask.views import MethodView
from flaskext.mysql import MySQL
from extensions import mysql, dbuser, dbpass, dbdb, dbhost
import simplejson as json


#mysqlUser = 'mugsyDbUser'
app = Flask(__name__)
api = Api(app)
app.config['MYSQL_DATABASE_USER'] = dbuser
app.config['MYSQL_DATABASE_PASSWORD'] = dbpass
app.config['MYSQL_DATABASE_DB'] = dbdb
app.config['MYSQL_DATABASE_HOST'] = dbhost

mysql.init_app(app)



class brewSettings(Resource):
	def get(self, userId):
		return {'getbrewSettings': requestBrewSettings.get(userId)}

class coffeeInfo(Resource):
	def get(self, coffeeTypeId):
		return {'getCoffeeInfo': getCoffeeInfo.get(coffeeTypeId)}


api.add_resource(brewSettings, '/getBrewSettings/<userId>')
api.add_resource(coffeeInfo, '/getCoffeeInfo/<coffeeTypeId>')


if __name__ == "__main__":
  #remove in production
	app.run(host = '192.168.1.183')

from flaskext.mysql import MySQL
