import getBrewSettings
from flask import Flask, request, jsonify
from flask_restful import Resource, Api
from flaskext.mysql import MySQL


app = Flask(__name__)
api = Api(app)

mysql.init_app(app)

class brewSettings(Resource):
	def get(self, userId):
		return {'getbrewSettings': getBrewSettings.get(userId)}


api.add_resource(brewSettings, '/getBrewSettings/<userId>')

if __name__ == "__main__":
	#remove host for production
	app.run(host = '192.168.1.183')
