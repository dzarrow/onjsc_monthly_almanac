import sys
from acis_request import *
from datetime import *

try:
    year = sys.argv[1]
    month = sys.argv[2]
except:
    year = "2012"
    month = "01"

dur = "mly"
sdate = '1800-' + month
edate = year + '-' + month
#today = datetime.now()
#if (int(year)==today.year and int(month)==today.month):
#    dur = "mtd"
#    sdate = '1800-' + month + '-01'
#    edate = year + '-' + month + '-' + str(today.day).zfill(2)
#else:
#    dur = "mly"
#    sdate = '1800-' + month
#    edate = year + '-' + month

elems = [
    {'name':'avgt', 'interval':'mly', 'duration':dur, 'reduce':{'reduce':'mean', 'add':'mcnt'}, 'maxmissing':5},
    {'name':'avgt', 'interval':'mly', 'duration':dur, 'reduce':'mean', 'normal':'departure'},
    {'name':'avgt', 'interval':'mly', 'duration':dur, 'reduce':{'reduce':'max', 'add':'date'}},
    {'name':'avgt', 'interval':'mly', 'duration':dur, 'reduce':{'reduce':'min', 'add':'date'}},

    {'name':'maxt', 'interval':'mly', 'duration':dur, 'reduce':{'reduce':'mean', 'add':'mcnt'}, 'maxmissing':5},
    {'name':'maxt', 'interval':'mly', 'duration':dur, 'reduce':'mean', 'normal':'departure'},
    {'name':'maxt', 'interval':'mly', 'duration':dur, 'reduce':{'reduce':'max', 'add':'date'}},
    {'name':'maxt', 'interval':'mly', 'duration':dur, 'reduce':'cnt_ge_90'},

    {'name':'mint', 'interval':'mly', 'duration':dur, 'reduce':{'reduce':'mean', 'add':'mcnt'}, 'maxmissing':5},
    {'name':'mint', 'interval':'mly', 'duration':dur, 'reduce':'mean', 'normal':'departure'},
    {'name':'mint', 'interval':'mly', 'duration':dur, 'reduce':{'reduce':'min', 'add':'date'}},
    {'name':'mint', 'interval':'mly', 'duration':dur, 'reduce':'cnt_le_32'},

    {'name':'pcpn', 'interval':'mly', 'duration':dur, 'reduce':{'reduce':'sum', 'add':'mcnt'}, 'maxmissing':5},
    {'name':'pcpn', 'interval':'mly', 'duration':dur, 'reduce':'sum', 'normal':'departure'},
    {'name':'pcpn', 'interval':'mly', 'duration':dur, 'reduce':{'reduce':'max', 'add':'date'}},
    {'name':'pcpn', 'interval':'mly', 'duration':dur, 'reduce':'cnt_gt_0'},

    {'name':'snow', 'interval':'mly', 'duration':dur, 'reduce':{'reduce':'sum', 'add':'mcnt'}, 'maxmissing':5},
    {'name':'snow', 'interval':'mly', 'duration':dur, 'reduce':'sum', 'normal':'departure'},
    {'name':'snow', 'interval':'mly', 'duration':dur, 'reduce':{'reduce':'max', 'add':'date'}},
    {'name':'snow', 'interval':'mly', 'duration':dur, 'reduce':'cnt_gt_0'}
    ]
input_dict = {'state':'NJ', 'date':edate, 'elems':elems, 'meta':['name','sids','state','county']}
acis_data = acis_request('MultiStnData',input_dict)

#COUNTIES
acis_county = acis_request('General/county', {'state':'NJ'})
counties = {}
for c in acis_county['meta']:
    counties[c['id']] = c['name']

final_data = []
for line in acis_data['data']:
    line['meta']['id'] = ""
    for id in line['meta']['sids']:
        id = id.split()
        if (id[1]=="2"):
            line['meta']['id'] = id[0]
    try:
        line['meta']['county'] = counties[line['meta']['county']]
    except:
        line['meta']['county'] = ""
    id = line['meta']['id']
    avgt = line['data'][0][0]
    maxt = line['data'][4][0]
    mint = line['data'][8][0]
    pcpn = line['data'][12][0]
    if (pcpn=="T"):
        pcpn = "0.00"
    snow = line['data'][16][0]
    if (snow=="T"):
        snow = "0.00"
    if (id!="" and (maxt!="M" or mint!="M" or avgt!="M" or pcpn!="M" or snow!="M")):
        elems = []
        elems_present = []
        acis_ranks2 = {'avgt':['M','M','M'], 'maxt':['M','M','M'], 'mint':['M','M','M'], 'pcpn':['M','M','M'], 'snow':['M','M','M']}
        if (maxt!="M"):
            elems_present.append('maxt')
            elems.append({'name':'maxt', 'interval':[1,0], 'duration':dur, 'reduce':'mean', 'maxmissing':5, 'smry':['cnt_gt_'+maxt, 'cnt_eq_'+maxt, 'cnt_ne_999'], 'smry_only':1})
        if (mint!="M"):
            elems_present.append('mint')
            elems.append({'name':'mint', 'interval':[1,0], 'duration':dur, 'reduce':'mean', 'maxmissing':5, 'smry':['cnt_gt_'+mint, 'cnt_eq_'+mint, 'cnt_ne_999'], 'smry_only':1})
        if (avgt!="M"):
            elems_present.append('avgt')
            elems.append({'name':'avgt', 'interval':[1,0], 'duration':dur, 'reduce':'mean', 'maxmissing':5, 'smry':['cnt_gt_'+avgt, 'cnt_eq_'+avgt, 'cnt_ne_999'], 'smry_only':1})
        if (pcpn!="M"):
            elems_present.append('pcpn')
            elems.append({'name':'pcpn', 'interval':[1,0], 'duration':dur, 'reduce':'sum', 'maxmissing':5, 'smry':['cnt_gt_'+pcpn, 'cnt_eq_'+pcpn, 'cnt_ne_999'], 'smry_only':1})
        if (snow!="M"):
            elems_present.append('snow')
            elems.append({'name':'snow', 'interval':[1,0], 'duration':dur, 'reduce':'sum', 'maxmissing':5, 'smry':['cnt_gt_'+snow, 'cnt_eq_'+snow, 'cnt_ne_999'], 'smry_only':1})
        input_dict = {'sid':line['meta']['sids'][0], 'sdate':sdate, 'edate':edate, 'elems':elems, 'meta':[]}
        acis_ranks = acis_request('StnData', input_dict)
        for n in range(len(acis_ranks['smry'])):
            acis_ranks2[elems_present[n]] = acis_ranks['smry'][n]
        line['data'].insert(2, acis_ranks2['avgt'])
        line['data'].insert(7, acis_ranks2['maxt'])
        line['data'].insert(12, acis_ranks2['mint'])
        line['data'].insert(17, acis_ranks2['pcpn'])
        line['data'].insert(22, acis_ranks2['snow'])
        final_data.append(line)

ofile = open('monthly_almanac/mly_'+year+'_'+month+'.json', 'w')
ofile.write(json.dumps(final_data))
ofile.close()
