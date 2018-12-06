from models import BrewSettings, CoffeeInfo, barcodeScanner
from flask import Flask, request, jsonify, make_response
from flask_restful import Resource, Api
from flask.views import MethodView
from flaskext.mysql import MySQL
from decafConfig import mysql, dbuser, dbpass, dbdb, dbhost
import simplejson as json


app = Flask(__name__)
api = Api(app)
app.config['MYSQL_DATABASE_USER'] = dbuser
app.config['MYSQL_DATABASE_PASSWORD'] = dbpass
app.config['MYSQL_DATABASE_DB'] = dbdb
app.config['MYSQL_DATABASE_HOST'] = dbhost

mysql.init_app(app)


#query brew settings by user id
class brewSettings(Resource):
	def get(self, userId):
		return {'brewSettings': requestBrewSettings.get(userId)}

#get info about coffee by coffeeTypeID
class coffeeInfo(Resource):
	def get(self, coffeeTypeId):
		return {'CoffeeInfo': getCoffeeInfo.get(coffeeTypeId)}

#get coffee info by using the barcode scanner
class barCode(Resource):
	def get(self):
		return {'barcodeScanner': barcodeScanner.get(self)}


api.add_resource(brewSettings, '/BrewSettings/<userId>')
api.add_resource(coffeeInfo, '/CoffeeInfo/<coffeeTypeId>')
api.add_resource(barCode, '/barcodeScanner/', methods=['GET'])


if __name__ == "__main__":
  #remove in production
	app.run(host = '192.168.1.183', debug=True)

from flaskext.mysql import MySQL


