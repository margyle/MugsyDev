import mugReader
from flask import Flask, request
from flask_restful import Resource, Api

app = Flask(__name__)
api = Api(app)



class readMugRFID(Resource):
	def get(self):
		return {'mugId': mugReader.readMug()}


api.add_resource(brewSettings, '/mugReader/')

if __name__ == "__main__":
	app.run(host= '192.168.1.183')
