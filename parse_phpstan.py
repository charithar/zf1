import json

with open('phpstan_json.json') as f:
    data = json.load(f)

relevant = {'function.deprecated', 'method.tentativeReturnType', 'class.serializable',
            'property.deprecated', 'method.deprecated', 'parameter.requiredAfterOptional',
            'staticMethod.void', 'method.void'}

print('=== PHP 8.x COMPATIBILITY ISSUES ===\n')
for filepath, file_data in sorted(data.get('files', {}).items()):
    msgs = [m for m in file_data.get('messages', []) if m.get('identifier', '') in relevant]
    if msgs:
        prefix = 'D:\\Sources\\php\\zf1\\library\\Zend\\'
        short_path = filepath[len(prefix):] if filepath.startswith(prefix) else filepath
        print(f'--- {short_path} ---')
        for m in msgs:
            print(f'  L{m["line"]}: [{m.get("identifier","")}] {m["message"]}')
        print()

from collections import Counter
counts = Counter()
for file_data in data.get('files', {}).values():
    for m in file_data.get('messages', []):
        ident = m.get('identifier', 'unknown')
        if ident in relevant:
            counts[ident] += 1

print('\n=== SUMMARY ===')
for k, v in counts.most_common():
    print(f'  {k}: {v}')