in_file = open("KVR.csv", 'r')
out_file = open("inserts.txt", 'w')

added = 0;
for line in in_file:
	cols = line.split("\t")
	if (len(cols) < 6):
		continue;
	insert = "INSERT INTO kamervragen (url,jaar, partij,titel,vraag,antwoord) VALUES ('{0}', '{1}', '{2}', '{3}', '{4}', '{5}');".format(cols[0], cols[1], cols[2], cols[3], cols[4], cols[5])
	added += 1;
	out_file.write(insert)

in_file.close()
out_file.close()

print added
