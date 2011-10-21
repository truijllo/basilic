#!/usr/bin/python
# -*- coding: iso-8859-1 -*-
# This file is based on the Basilic system
# Copyright (C) 2004  Gilles Debunne (Gilles.Debunne@imag.fr)
# Version 1.5.14, packaged on May 2, 2007.
# 
# http://artis.imag.fr/Software/Basilic
# 
# adapted to be compliant to the enhanced version of BasilicPlus.
# Basilic  is  free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published
# by the Free Software Foundation; either version 2 of the License,
# or (at your option) any later version.
# 
# Basilic  is  distributed  in the hope that it will be useful, but
# WITHOUT  ANY  WARRANTY ; without  even  the  implied  warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
# General Public License for more details.
# 
# You should have received a copy of the GNU General Public License
# along with Basilic; if not, write to the Free Software Foundation
# Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

from custom_support import *

# needed python-bibtex
# pip install python-bibtex
import _bibtex
import sys
import re

author_rex = re.compile('\s+and\s+')
nl_marker='~$@$#$~'

class bib_iterator(object):
    def __init__(self,args):
        self.current_item = 0
        self.data=args
    def name(self):
        return self.data[self.current_item]
    def __iter__(self):
        return self
    def next(self):
        if (self.current_item == (len(self.data))-1):
	    raise StopIteration
	else:
	    self.current_item += 1
	    return self.data[self.current_item-1]
        
class author_obj(object):
    def __init__(self,data):
	self.author=data
    def __getdata(self,data):
	noNone=lambda x: x or ""
	return " ".join(map(noNone,data))
    def last(self):
	# per me e' piu' corretto cosi'...
	#return self.__getdata(self.author[:2])	    
	return self.__getdata(self.author[2:]).strip()  
    def first(self):
	# per me e' piu' corretto cosi'...
	#return self.__getdata(self.author[2:])
	return self.__getdata(self.author[:2]).strip()	    
    def __repr__(self):
	return self.__str__()
    def __str__(self):
	return self.__getdata(self.author)
	
	
	
class bib_object(object):
    def _field(self,fieldname):
	try:
	    return getattr(self,fieldname)
	except AttributeError:
	    return None
    def _get_data(self):
        return self.__dict__
    def _isMissing(self,fieldname):
        if not( self._field(fieldname) ):
	    return True
        return False
    def _key(self):
	return self._field('bibTex')
    def _fixYear(self):
	setattr(self,'year',self._field('year')[2])
    def _fixAuthors(self, flag=False):
        if flag and self._isMissing('author'):
	    author_rex = re.compile('\s+and\s+')
	    author_list = author_rex.split(self._data['author'])
	    tmp=[]
	    for i in author_list:
		a=i.strip().split()
		ta=[None," ".join(a[:-1]),a[-1],None]
		tmp.append(ta[:])
	    author_list=tmp[:]
	    setattr(self,'author',[author_obj(i) for i in author_list])
	    return True
	if not self._isMissing('author'):
	    if not flag:
		auth=self._data['author']
		noNone=lambda x: x or ""
		#setattr(self,'author',[" ".join(map(noNone,i)).strip() for i in self._field('author')])
		setattr(self,'author',[author_obj(i) for i in self._field('author')])
	    
    def _firstField(self):
        ##attributes=[ bib_structure(name=i) for i in dir(self) if not i.startswith('_')]
        attributes=[ i for i in self.__dict__.keys() if i not in ("_data")]
        #return self.__iter__(attributes)
	return bib_iterator(attributes)
            
class bib_entry(bib_object):
    def __init__(self,data_dict={}):
        offset=-1
        self._data=data_dict
	self.note=""
        for i in self._data:
            setattr(self,i,self._data[i])
        if not self._isMissing('author'):
            self._fixAuthors()
        if not self._isMissing('year'):
	    self._fixYear()
        
        
def expand (f, entry, typemap = -1):
    items = entry [4]
    for k in items.keys ():
        items [k] = _bibtex.expand (f, items [k], typemap)
        if k!='year':
            items[k]=items[k][-1]
    items['type']=entry[1]
    items['bibTex']=entry[0]
    return items
        
def fix_invalid_formats(filename,fields):
    f=file(filename,'r')
    r=f.readlines()
    f.close()
    for field in fields:
	for i in xrange(len(r)):
	    if r[i].startswith(field):
		tmp=r[i][:r[i].find("}")]
		if (tmp.count(", ")==1) and (tmp[tmp.find(","):].count(" ")==1):
		    tmp=tmp.replace(",","")
		if (", " in tmp ) and (" and " in tmp):
		    tmp=tmp.replace(",","")
		for char in [",",":",":",";","|"]:
		    tmp=tmp.replace(char," and ")
		for char in ["*","~"]:
		    tmp=tmp.replace(char,"")
		for char in [".",".  "]:
		    tmp=tmp.replace(char,". ")
		r[i]=tmp+"},\n"
    f=file(filename,'w')
    f.write("".join(r))
    f.close()

def read_and_parse_file(filename,strict=1):
    fix_invalid_formats(filename,["author = ","editor = "])
    f = _bibtex.open_file (filename, strict)
    l=[]
    while 1:
        try:
            entry = _bibtex.next (f)
            if entry is None: break
            l.append(bib_entry(expand (f, entry)))
        except IOError, msg:
                obtained = 'ParserError'
    return l[:]



def main(entries):
    badType=False
    for e in entries:
        if stdtype(e.type) not in validTypes:
            badType=True
            print ">> Unrecognized type ("+e.type+") for "+publi(e)
    
    if badType:
        print "\nValid publication types are:"
        for t in validTypes:
            print " "+t,
        print "\nChange publication type or remove entry (or add this new type in the database)."
        sys.exit(0)

           # Look for missing required fields
        missingField = 0
        for e in entries:
            missingField=check(e)
        
        if missingField:
            print "\n"+str(missingField)+" missing required fields."
            sys.exit(0)
            
    # Look for extra fields that will be lost
    extraField=0
    for e in entries:
        ty = stdtype(e.type)
        h = e._firstField()
        while h:
            if h.name() not in ("author","type","bibTex") and h.name() not in fields[ty]:
		e.note+=h.name()+": "+clean(getattr(e,h.name()))+nl_marker
                print ">> " + e.bibTex + " | Field \""+h.name()+"\" (\""+clean(getattr(e,h.name()))+"\"), is odd, added in note field." 
                if (h.name()=="page"): print "  >> \"page\" should be spelled \"pages\""
                whereAvailable=[]
                for t in validTypes:
                    if h.name() in fields[t]:
                        whereAvailable.append(t)
                if len(whereAvailable):
                    print "   \""+h.name()+"\" is only valid for entries of type:",
                    for w in whereAvailable:
                        print w+",",
                    print
                extraField=extraField+1
	    try:
		h.next()
	    except StopIteration:
		break
	e.note=e.note.strip()
    
    if extraField:
        print "\n"+str(extraField)+" non standard fields will be lost during conversion (see messages above)."
        print "If you want to preserve these informations, edit "+sys.argv[1]+" and change the fields' names,"
        print "check their spelling or convert these fields into a \"note\". Then re-run "+sys.argv[0]+".\n"
    
    
    # Titles, journals and booktitles listing
  
    titles = []
    journals = []
    booktitles = []
    for e in entries:
        titles.append(clean(e._field("title")))
        if len(str(e._field("booktitle") or "")): 
	    booktitles.append(clean(e._field("booktitle")))
        if len(str(e._field("journal") or "")): 
	    journals.append(clean(e._field("journal")))
    
    # Create author list and search for duplicates
    first = []
    last = []
    AUTHORid = {}
    for e in entries:
        author = e._field("author")
	ty=stdtype(e.type)
	if ty=="Book" and e._isMissing('author') and not (e._isMissing('editor')):
	    e._data['author']=e.editor
	    e._fixAuthors(True)
        if e._isMissing('author'):
            print "No authors given for publication "+publi(e)
            ty=stdtype(e.type)
            if ty in ("Booklet","Manual","Misc"):
                print "  The \"author\" field is optional in the standard for type",ty,"."
                print "  It is however here required in order to automatically compute the bibTex key."
	    sys.exit(0)
        else:
            auth = e._field('author')
            for a in auth:
                index=len(first)
                fi = clean(a.first())
                first.append(fi)
                last.append(a.last())
                la=clean(a.last())
		print e.bibTex
                NAME=authorKey(fi, la)
                if AUTHORid.has_key(NAME):
                    AUTHORid[NAME].append(index)
                else:
                    AUTHORid[NAME] = [index]
    
    
    titles.sort()
    journals.sort()
    booktitles.sort()
	
	
    f = file("titleList.txt", 'w')
    f.write("## List of titles : check spelling and remove spurious braces\n\n")
    for t in titles:
        f.write(t+"\n")
    f.close()
    
    # Journal listing
    f = file("journalList.txt", 'w')
    f.write("## List of journals\n\n")
    f.write("## Clear duplicates, correct and uniformize spellings to shorten the list as much as possible.\n")
    f.write("## Remove year, number and volume from names. Expand acronyms if needed.\n")
    f.write("## Final list should only contains different journals.\n\n")
    prev = ""
    for j in journals:
        if j != prev:
            f.write(j+"\n")
            prev = j
    f.close()
    
    f = file("booktitleList.txt", 'w')
    f.write("## List of booktitles\n\n")
    f.write("## Clear duplicates, correct and uniformize spellings to shorten the list as much as possible.\n")
    f.write("## \"Proceedings of ConfFoo\", \"Proc. of ConfFoo\"  and \"ConfFoo'04\" should for instance\n")
    f.write("## be merged, probably in \"Proceedings of ConfFoo\". Remove years from booktitles' names.\n")
    f.write("## Final list should only contain different InProceedings booktitles.\n\n")
    prev = ""
    for b in booktitles:
        if b != prev:
            f.write(b+"\n")
            prev = b
    f.close()
    
    # Search for duplicated titles
    TITLES = []
    for t in titles:
        TITLES.append(t.upper())
    
    nb=0
    for t in titles:
        nb=nb+1
        SUBTITLES=TITLES[nb:]
        if SUBTITLES.count(t.upper()) != 0:
            print ">> Title \""+t+"\" is duplicated. Should be checked."
    
    print
    
    f = file("authorList.txt", 'w')
    f.write("## List of authors\n\n")
    f.write("## Look for duplicates.\n")
    f.write("## Complete first names when possible, check spelling.\n")
    f.write("## Convert into latin characters or remove LaTeX specific commands.\n")
    f.write("## Names listed on the same line will actually be merged and replaced by the first one.\n\n")
    
    NAMES = AUTHORid.keys()
    NAMES.sort()
    
    for NAME in NAMES:
        names = []
        for i in AUTHORid[NAME]:
            names.append(last[i]+", "+first[i])
        old=""
        for new in names:
            if new != old:
                if old!="":
                    f.write("\t\t(aka) ")
                f.write(new)
                if new.find("{") != -1 or new.find("}") != -1:
                    f.write("\t(Check brace)")
                else:
                    if new.find("\\") != -1:
                        f.write("\t(Check the \\)")
                old=new
        f.write("\n")
    f.close()
    
    # Write out a "BibTeX key" to "Basilic key" translation file.
    # This is just to help users populate their publication directories.
    # Advanced users may want to comment out these lines
    allBibtex = []
    
    f = file("keys.txt", 'w')
    f.write("## Debug : lists new Basilic bibTex keys that differ from previous ones.\n\n")
    f.write("## Only interesting if you update an existing basilic installation\n")
    f.write("## Reorder entries in the bibtex if KEY04 and KEY04b are swaped.\n")
    f.write("## You should empty this file. Otherwise, you'll have to rename dirs.\n")
    f.write("\n# Generated key / Key in file.\n")
    nbDifferentKey = 0
    for e in entries:
        genKey=bibTexKey(e,allBibtex)
        if genKey != e._key():
            f.write(genKey+"\t"+e._key()+"\n");
            nbDifferentKey = nbDifferentKey+1
    f.close();
    if nbDifferentKey != 0:
        print nbDifferentKey,"keys are different.\n"

    
    
    print "Parsing complete. You should now open the following files:"
    print "  authorList.txt, booktitleList.txt, journalList.txt and titleList.txt."
    print "Modify "+sys.argv[1]+" according to the advice given in them."
    print "Re-run "+sys.argv[0]+" and re-check the files until your bibTex is clean."
    
    
    # Databases creation
    #  Authors
    f = file("authors.sql", 'w')
    authorId = {}
    idAuthor=1
    for NAME in NAMES:
        f.write("INSERT INTO authors (id,first,last) VALUES ("+str(idAuthor)+", '"+iso(first[AUTHORid[NAME][0]]).replace("'","\\'")+"', '"+iso(last[AUTHORid[NAME][0]]).replace("'","\\'")+"');\n")
        authorId[NAME] = idAuthor
        idAuthor = idAuthor+1
    f.close
      
    publiFields = ["address","booktitle","chapter","edition","editor","howpublished","institution","journal","keywords","month","note","number","key","organization","pages","publisher","school","series","title","type","volume","year"]
    
    fp = file("publis.sql", 'w')
    fpa = file("publiauthors.sql", 'w')
    idPubli=1
    
    for e in entries:
        ty = stdtype(e._field('type'))
        fp.write("INSERT INTO publis VALUES ("+str(idPubli)+", '"+bibTexKey(e,allBibtex)+"', '"+ty+"'")
        for f in publiFields:
            if f in fields[ty]:
                fi=e._field(f)
                if e._isMissing(f):
                    fp.write(", ''")
                else:
                    fp.write(", '"+clean(fi).replace(nl_marker,'\n')+"'")
            else:
                fp.write(", ''")
	fp.write(",'','','','',NULL,'',1,'',1,NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00'");
        fp.write(");\n")
    
        author = e._field("author")
        if e._isMissing('author'):
            print "No author given for publication", publi(e)
        else:
            auth = author
            rank=1
            for a in auth:
                fi = clean(a.first())
                la = clean(a.last())
                aId = authorId[authorKey(fi, la)]
                fpa.write("INSERT INTO publiauthors VALUES ("+str(aId)+", "+str(idPubli)+", "+str(rank)+");\n")
                rank=rank+1
        idPubli = idPubli+1
    fp.close()
    fpa.close()
    
    print "\nOnce this is done, import the mySQL queries listed in"
    print " authors.sql, publiauthors.sql and publis.sql"
    print "to fill the databases. Enjoy !"
	
	
if __name__=="__main__":
    if len(sys.argv) != 2:
        print "Usage : "+sys.argv[0]+" bibfile"
        sys.exit(0)

    bib_list=read_and_parse_file(sys.argv[1])
    main(bib_list)
