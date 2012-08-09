from acis_request import *
from cStringIO import StringIO
import cairo

def createImage(data, ofilename):
    img_buf = StringIO(data[21:].decode('base64'))
    img_buf.seek(0)
    out_img = cairo.ImageSurface.create_from_png(img_buf)
    ofile = open(ofilename,'w')
    out_img.write_to_png(ofile)
    ofile.close()

try:
    year = sys.argv[1]
    month = sys.argv[2]
except:
    year = "2012"
    month = "07"

input_dict = {"state":"nj","grid":"3","elems":[{"name":"avgt","interval":"mly","duration":"mly","reduce":"mean"}],"image":{"proj":"lcc","overlays":["county:0.25:black","state:2:black"],"interp":"cspline","cmap":"jet","width":200,"levels":range(0,100,2)},"date":year+'-'+month}
acis = acis_request('GridData', input_dict)
createImage(acis['data'], 'monthly_almanac/mly_'+year+'_'+month+'_avgt.png')

input_dict = {"state":"nj","grid":"3","elems":[{"name":"maxt","interval":"mly","duration":"mly","reduce":"mean"}],"image":{"proj":"lcc","overlays":["county:0.25:black","state:2:black"],"interp":"cspline","cmap":"jet","width":200,"levels":range(0,100,2)},"date":year+'-'+month}
acis = acis_request('GridData', input_dict)
createImage(acis['data'], 'monthly_almanac/mly_'+year+'_'+month+'_maxt.png')

input_dict = {"state":"nj","grid":"3","elems":[{"name":"mint","interval":"mly","duration":"mly","reduce":"mean"}],"image":{"proj":"lcc","overlays":["county:0.25:black","state:2:black"],"interp":"cspline","cmap":"jet","width":200,"levels":range(0,100,2)},"date":year+'-'+month}
acis = acis_request('GridData', input_dict)
createImage(acis['data'], 'monthly_almanac/mly_'+year+'_'+month+'_mint.png')

input_dict = {"state":"nj","grid":"3","elems":[{"name":"pcpn","interval":"mly","duration":"mly","reduce":"sum"}],"image":{"proj":"lcc","overlays":["county:0.25:black","state:2:black"],"interp":"cspline","cmap":"jet","width":200,"levels":[0.01,0.10,0.25,0.50,0.75,1.00,1.50,2.00,2.50,3.00,4.00,5.00,6.00,8.00,12.00]},"date":year+'-'+month}
acis = acis_request('GridData', input_dict)
createImage(acis['data'], 'monthly_almanac/mly_'+year+'_'+month+'_pcpn.png')

#input_dict = {"state":"nj","grid":"3","elems":[{"name":"snow","interval":"mly","duration":"mly","reduce":"sum"}],"image":{"proj":"lcc","overlays":["county:0.25:black","state:2:black"],"interp":"cspline","cmap":"jet","width":200,"levels":[0.01,0.10,0.25,0.50,0.75,1.00,1.50,2.00,2.50,3.00,4.00,5.00,6.00,8.00,12.00]},"date":year+'-'+month}
input_dict = {"state":"nj","grid":"3","elems":[{"name":"pcpn","interval":"mly","duration":"mly","reduce":"sum"}],"image":{"proj":"lcc","overlays":["county:0.25:black","state:2:black"],"interp":"cspline","cmap":"jet","width":200,"levels":[100,200]},"date":year+'-'+month}
acis = acis_request('GridData', input_dict)
createImage(acis['data'], 'monthly_almanac/mly_'+year+'_'+month+'_snow.png')
