import sys, urllib2
try:
    import json
except:
    import simplejson as json

def acis_request(method,params):
    base_url = 'http://data.rcc-acis.org/'
    req = urllib2.Request(base_url+method,
                          json.dumps(params),
                          {'Content-Type':'application/json'})
    try:
        response = urllib2.urlopen(req)
        acis = json.loads(response.read())
        if ('error' in acis):
            print acis['error']
            sys.exit(0)
        return acis
    except urllib2.HTTPError:
        error = urllib2.HTTPError
        if error.code == 400:
            print error.msg
