# -*- coding: iso-8859-1 -*-

# CONSTANTS AND SUPPORT ROUTINES

import string

validTypes = ("Article","Book","Booklet","InBook","InCollection","InProceedings","Manual","PhdThesis","Misc","MastersThesis","TechReport","Proceedings","Unpublished")
VALIDTYPES = []
requiredFields = {}
optionalFields = {}

for t in validTypes:
    VALIDTYPES.append(t.upper())
    requiredFields[t] = ["title","year"]
    optionalFields[t] = ["month","key","keywords"]

requiredFields["Article"].append("journal")
optionalFields["Article"].append("volume");
optionalFields["Article"].append("number");
optionalFields["Article"].append("pages");
optionalFields["Article"].append("note");

requiredFields["Book"].append("publisher");
optionalFields["Book"].append("volume"); # volume or number
optionalFields["Book"].append("number");
optionalFields["Book"].append("series");
optionalFields["Book"].append("address");
optionalFields["Book"].append("edition");
optionalFields["Book"].append("note");

optionalFields["Booklet"].append("howpublished");
optionalFields["Booklet"].append("address");
optionalFields["Booklet"].append("note");

requiredFields["InBook"].append("publisher");
optionalFields["InBook"].append("volume");
optionalFields["InBook"].append("number");
optionalFields["InBook"].append("chapter"); # chapter and/or pages
optionalFields["InBook"].append("pages");   #
optionalFields["InBook"].append("series");
optionalFields["InBook"].append("type");
optionalFields["InBook"].append("address");
optionalFields["InBook"].append("edition");
optionalFields["InBook"].append("note");

requiredFields["InCollection"].append("booktitle");
requiredFields["InCollection"].append("publisher");
optionalFields["InCollection"].append("editor");
optionalFields["InCollection"].append("volume");
optionalFields["InCollection"].append("number");
optionalFields["InCollection"].append("series");
optionalFields["InCollection"].append("type");
optionalFields["InCollection"].append("chapter");
optionalFields["InCollection"].append("pages");
optionalFields["InCollection"].append("address");
optionalFields["InCollection"].append("edition");
optionalFields["InCollection"].append("note");

requiredFields["InProceedings"].append("booktitle");
optionalFields["InProceedings"].append("editor");
optionalFields["InProceedings"].append("volume");
optionalFields["InProceedings"].append("number");
optionalFields["InProceedings"].append("series");
optionalFields["InProceedings"].append("pages");
optionalFields["InProceedings"].append("address");
optionalFields["InProceedings"].append("organization");
optionalFields["InProceedings"].append("publisher");
optionalFields["InProceedings"].append("note");

optionalFields["Manual"].append("organization");
optionalFields["Manual"].append("address");
optionalFields["Manual"].append("edition");
optionalFields["Manual"].append("note");

requiredFields["PhdThesis"].append("school");
optionalFields["PhdThesis"].append("type");
optionalFields["PhdThesis"].append("address");
optionalFields["PhdThesis"].append("note");
requiredFields["MastersThesis"].append("school");
optionalFields["MastersThesis"].append("type");
optionalFields["MastersThesis"].append("address");
optionalFields["MastersThesis"].append("note");

optionalFields["Misc"].append("howpublished");
optionalFields["Misc"].append("note");

optionalFields["Proceedings"].append("editor");
optionalFields["Proceedings"].append("volume");
optionalFields["Proceedings"].append("number");
optionalFields["Proceedings"].append("series");
optionalFields["Proceedings"].append("address");
optionalFields["Proceedings"].append("organization");
optionalFields["Proceedings"].append("publisher");
optionalFields["Proceedings"].append("note");

requiredFields["TechReport"].append("institution");
optionalFields["TechReport"].append("type");
optionalFields["TechReport"].append("number");
optionalFields["TechReport"].append("address");
optionalFields["TechReport"].append("note");

requiredFields["Unpublished"].append("note");

fields = {}
for t in validTypes:
    fields[t] = []
    for r in requiredFields[t]:
        fields[t].append(r)
    for o in optionalFields[t]:
        fields[t].append(o)

isoa = dict()
isob = dict() # For expressions of iso enclosed into braces
isoa["\\`A"] = "�"
isoa["\\'A"] = "�"
isoa["\\~A"] = "�"
isoa["\\^A"] = "�"
isoa["\\rA "] = "�"
isoa["\\AA "] = "�"
isoa["\\AE "] = "�"
isoa["\\`E"] = "�"
isoa["\\'E"] = "�"
isoa["\\^E"] = "�"
isoa["\\`I"] = "�"
isoa["\\'I"] = "�"
isoa["\\^I"] = "�"
isoa["\\O"] = "�"
isoa["\\`O"] = "�"
isoa["\\'O"] = "�"
isoa["\\^O"] = "�"
isoa["\\~O"] = "�"
isoa["\\`U"] = "�"
isoa["\\'U"] = "�"
isoa["\\^U"] = "�"
isoa["\\'Y"] = "�"
isoa["\\c{C}"] = "�"
isoa["\\~N"] = "�"
isoa["\\ss "] = "�"
isoa["\\`a"] = "�"
isoa["\\'a"] = "�"
isoa["\\~a"] = "�"
isoa["\\^a"] = "�"
isoa["\\ra "] = "�"
isoa["\\aa "] = "�"
isoa["\\ae "] = "�"
isoa["\\`e"] = "�"
isoa["\\'e"] = "�"
isoa["\\^e"] = "�"
isoa["\\`i"] = "�"
isoa["\\'i"] = "�"
isoa["\\^i"] = "�"
isoa["\\`\\i"] = "�"
isoa["\\'\\i"] = "�"
isoa["\\^\\i"] = "�"
isoa["\\`{\\i}"] = "�"
isoa["\\'{\\i}"] = "�"
isoa["\\^{\\i}"] = "�"
isoa["\\o"] = "�"
isoa["\\`o"] = "�"
isoa["\\'o"] = "�"
isoa["\\^o"] = "�"
isoa["\\~o"] = "�"
isoa["\\`u"] = "�"
isoa["\\'u"] = "�"
isoa["\\^u"] = "�"
isoa["\\'y"] = "�"
isoa["\\\"y"] = "�"
isoa["\\c{c}"] = "�"
isoa["\\~n"] = "�"
isoa["\\~{n}"] = "�"
isoa["\\th "] = "�"
isoa["\\dh "] = "�"
isoa["\\TH "] = "�"
isoa["\\DH "] = "�"
isoa["\\{th}"] = "�"
isoa["\\{dh}"] = "�"
isoa["\\{TH}"] = "�"
isoa["\\{DH}"] = "�"

isoa["\\\"A"] = "�"
isoa["\\\"E"] = "�"
isoa["\\\"I"] = "�"
isoa["\\\"O"] = "�"
isoa["\\\"U"] = "�"
isoa["\\\"a"] = "�"
isoa["\\\"e"] = "�"
isoa["\\\"i"] = "�"
isoa["\\\"\\i"] = "�"
isoa["\\\"{\\i}"] = "�"
isoa["\\\"o"] = "�"
isoa["\\\"u"] = "�"
isoa["\\\"Y"] = "�"
isoa["\\\"y"] = "�"

isob["{\\\"O}"] = "�"
isob["{\\\"I}"] = "�"
isob["{\\\"A}"] = "�"
isob["{\\\"E}"] = "�"
isob["{\\\"U}"] = "�"
isob["{\\\"a}"] = "�"
isob["{\\\"e}"] = "�"
isob["{\\\"i}"] = "�"
isob["{\\\"{\\i}}"] = "�"
isob["{\\\"\\i}"] = "�"
isob["{\\\"o}"] = "�"
isob["{\\\"u}"] = "�"
isob["{\\\"Y}"] = "�"
isob["{\\\"y}"] = "�"


def iso(w):
    for l in isob:
        w = w.replace(l, isob[l])
    for l in isoa:
        w = w.replace(l, isoa[l])
    return w

def stdtype(t):
    if t in validTypes:
        return t
    else:
        if t.upper() in VALIDTYPES:
            index = VALIDTYPES.index(t.upper())
            return validTypes[index]
        else:
            return "Unknown type"
        
def clean(i):
    i=str(i)
    i=i.strip()
    i=i.replace("\n","")
    # i=i.replace("{","")
    # i=i.replace("}","")
    while i.find("  ") != -1:
        i=i.replace("  "," ")
    i=iso(i)
    return i.replace("'","\\'")



def publi(entry):
    return "\""+clean(entry._field("title"))+"\", bibTex %s" % entry._field("bibTex")


def check(entry):
    missingField=0
    ty = stdtype(entry.type())
    for req in requiredFields[ty]:
        if entry.isMissing(req):
            print "Required field",req,"is not present in "+publi(entry)
            missingField=missingField+1
            if req=="year":
                if ty in ("Booklet","Manual","Misc","Unpublished"):
                    print "The \"year\" field is optional in the standard for type",ty+"."
                    print "It is however here required in order to automatically compute the bibTex key."
            if req=="title" and ty=="Misc":
                print "The \"title\" field is optional in the standard for type",ty
                print "It is however here required as it has to appear in the publication listing"
        else:
            if len(str(entry.field(req)).strip()) == 0:
                print "Field",req,"is empty in "+publi(entry)
                missingField=missingField+1
    if ty in ("Book","InBook","InCollection","InProceedings","Proceedings"):
        if not entry.isMissing("number") and not entry.isMissing("volume"):
            print "Only volume or number is accepted, not both : "+publi(entry)
            missingField=missingField+1
    if ty=="InBook":
        if entry.isMissing("chapter") and entry.isMissing("pages"):
            print "Fields \"chapter\" and/or \"pages\" must be given for : "+publi(entry)
            missingField=missingField+1
    if ty=="Proceedings":
        if not entry.isMissing("editor"):
            print "Problem with the \"editor\" field in",publi(entry)
            if entry.isMissing("author"):
                print "  You should rename the field \"editor\" into \"author\""
            else:
                print "  You should merge the \"editor\" and \"author\" into an \"author\" field (or rename \"editor\" as \"note\")"
            # print "  This will be automatic in future releases"
    if ty in ("Book","InBook"):
        if not entry.isMissing("editor"):
            print "\"author\" should replace \"editor\" in",ty,"entries : ",publi(entry)
            if entry.isMissing("author"):
                print "  You should replace \"editor\" by \"author\""
            else:
                print "  You should merge \"editor\" and \"author\", or rename \"editor\" as \"note\""
            # print "  This will be automatic in future releases"
    return missingField






def authorKey(first, last):
        return last.upper()+" "+(first.upper())[0]
#    try:
#        return last.upper()+" "+(first.upper())[0]
#    except:
#        if not first:
#            return last.upper()+" "+()[0]
  
def bibTexKey(entry,allBibtex):
    bibtex=""
    e=entry._field('bibTex')
    #if not(e):
    if 1:
        auth = entry._field("author")
        if len(auth) == 1:
            bibtex = ((string.join(auth[0].last()))[0:3]).upper().replace(" ","")
        else:
            for a in auth:
                bibtex = bibtex + (string.join(a.last()).upper())[0]
        bibtex = bibtex+str(entry._field("year"))[2:4]
    #else:
    #    bibtex=e
    if bibtex in allBibtex:
        root = bibtex
        index = ord('a')
        while bibtex in allBibtex:
            bibtex = root+chr(index)
            index = index+1
    allBibtex.append(bibtex)
    return bibtex
