import MySQLdb
import os

##############################################
### TO EDIT !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
HOST="DB_HOST"
DB="basilic"
USER="DB_USER"
PASSWD="DB_PASSWORD"
PATH="/var/www/basil/research/publications/"
###############################################




indexYear="""<?php $year=%s; include("../index.php"); ?>"""
indexBib="""<?php $id=%s; include("../../publi.php"); ?>"""

def connectDB():
        #dbconn = MySQLdb.connect("host=%s dbname=%s user=%s password=%s" % (HOST,DB,USER,PASSWD))
	dbconn = MySQLdb.connect(host=HOST, db=DB,  user=USER,  passwd=PASSWD)
        cursore=dbconn.cursor()
        return cursore,dbconn

def makeQuery(cur,query):
	cur.execute(query)
	return cur.fetchall()

def getYears(cur):
	return makeQuery(cur,"select distinct year from publis")

def getBibs(cur):
	return makeQuery(cur,"select id,bibTex,year,note from publis")

def getText(note,marker="abstract:"):
	note=note.split('\n')
	for i in note:
		if i.startswith(marker):
			return i[len(marker):].strip()
	return None

def updateNote(cur,bib,note):
	return makeQuery(cur,"""update publis set note="%s" where id=%s """ % (note,bib))

def updateURL(cur,bib,url):
	return makeQuery(cur,"""update publis set url="%s" where id=%s """ % (url,bib))


def closeDB(cur):
	makeQuery(cur,"update publis set note='' where note='idxproject: ?';")
	return makeQuery(cur,"insert into publiequip select 1,id,1 from publis")

def insertAbstract(cur,bib_id,abstract):
	cur.execute("insert into docs (type,source,size,sizeX,sizeY,protect,dt_create) values ('ABS','abstract.html',%s,0,0,'public',now());" % len(abstract))
	cur.execute("insert into publidocs values ( (select max(id) from docs), %s)" % bib_id)
	return True

def createFile(text,filename,path):
	f=file(path+"/%s" % filename,'w')
	f.write(text)
	f.close()

def main():
	cur,dbconn=connectDB()
	years=getYears(cur)
	years=[i[0] for i in years]
	bibs=getBibs(cur)
	for i in years:
		os.system("mkdir %s/%s" % (PATH,i))
		createFile(indexYear % i, 'index.php',"%s/%s" % (PATH,i))
	for i in bibs:
		os.system("mkdir %s/%s/%s" % (PATH,i[2],i[1]))
		createFile(indexBib % i[0], 'index.php',"%s/%s/%s" % (PATH,i[2],i[1]) )
	for i in bibs:
		ab=getText(i[3],"abstract:")
		url=getText(i[3],"url:")
		note=i[3]
		if ab:
			createFile(ab, 'abstract.html',"%s/%s/%s" % (PATH,i[2],i[1]) )
			insertAbstract(cur,i[0],ab)
			note=note.replace("abstract: "+ab,'').strip()
			updateNote(cur,i[0],note)
		if url:
			updateURL(cur,i[0],url.strip())
			note=note.replace("url: "+url,'')
			updateNote(cur,i[0],note.replace('"','').strip())
	closeDB(cur)
	os.system("chown www-data:www-data -R %s " % PATH)
	print "done"

if __name__=="__main__":
	main()
