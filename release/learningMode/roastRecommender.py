import pandas as pd
import numpy as np
from sklearn.feature_extraction.text import CountVectorizer
from sklearn.metrics.pairwise import cosine_similarity
from pyfiglet import Figlet
import colorama
from colorama import Fore, Back, Style
df = pd.read_csv("arabicaWithBrands.csv")

f = Figlet(font='standard')

#select the valuable attribute columns
features = ['species','brand','country','region','altitude','company','aroma','flavor','sweetness','body','acidity','balance','uniformity','cupperPoints','totalPoints']

#combine column values into single string
def combine_features(row):
 return row['species']+" "+row['brand']+" "+row['country']+" "+row['region']+" "+row['altitude']+" "+row['company']+" "+str(row['aroma'])+" "+str(row['flavor'])+" "+str(row['sweetness'])+" "+str(row['body'])+" "+str(row['acidity'])+" "+str(row['balance'])+" "+str(row['uniformity'])+" "+str(row['cupperPoints'])+" "+str(row['totalPoints'])

for feature in features:
#clean and preprocess data, fill NaN with blank as needed
    df[feature] = df[feature].fillna('') 
#run combined_features() method over each rows of dataframe and push combined string to “combined_features”
df["combined_features"] = df.apply(combine_features,axis=1) 

#creating new CountVectorizer() object
cv = CountVectorizer() 
#feed combined strings to CountVectorizer() object
count_matrix = cv.fit_transform(df["combined_features"]) 

#get the cosign similarity matrix from the count matrix
cosine_sim = cosine_similarity(count_matrix)

#helpers to get company from index and index from company
def get_company_from_index(index):
    return ", Roast ID#: " + str(df[df.index == index]["index"].values[0]) + ", Brand: " + df[df.index == index]["brand"].values[0]+ ", Roast Profile Points: " + str(df[df.index == index]["totalPoints"].values[0]) + "simscore" + cosine_sim

def get_index_from_company(company):
    return df[df.company == company]["index"].values[0]

print("\n\n\n")
print(f.renderText('Mugsy ML'))
inputCoffee = input("Select Coffee To Match: ")
#print ("you entered " + input_var) 

company_user_likes = inputCoffee #ucda
coffee_index = get_index_from_company(company_user_likes)


#get selected coffee row and get similarity scores from coffee index
similar_coffees = list(enumerate(cosine_sim[coffee_index])) 

#sort by similarity scores in descending order
sorted_similar_coffees = sorted(similar_coffees,key=lambda x:x[1],reverse=True)[1:]


#loop and print x entries from similar_coffees
i=0
print(Fore.WHITE+"Mugsy Learning Mode: 10 similar coffees to "+company_user_likes+" are:\n")
for element in sorted_similar_coffees:
    print(str(i) +":"+ get_company_from_index(element[0]) )
    i=i+1
    if i>9:
        break

