import 'package:flutter/material.dart';

class ThreatHuntingScreen extends StatefulWidget {
  const ThreatHuntingScreen({super.key});

  @override
  State<ThreatHuntingScreen> createState() => _ThreatHuntingScreenState();
}

class _ThreatHuntingScreenState extends State<ThreatHuntingScreen> {
  final TextEditingController _searchController = TextEditingController();
  String _selectedHuntType = 'IOC';
  List<Map<String, dynamic>> _searchResults = [];
  bool _isSearching = false;

  final List<Map<String, dynamic>> _iocLibrary = [
    {'type': 'IP', 'value': '185.130.5.253', 'threat': 'C2 Server', 'confidence': 'High'},
    {'type': 'Domain', 'value': 'malware-domain.com', 'threat': 'Malware Distribution', 'confidence': 'High'},
    {'type': 'Hash', 'value': 'e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855', 'threat': 'Ransomware', 'confidence': 'Critical'},
    {'type': 'URL', 'value': 'http://evil.com/payload', 'threat': 'Phishing', 'confidence': 'Medium'},
  ];

  Future<void> _performHunt() async {
    if (_searchController.text.isEmpty) return;
    
    setState(() {
      _isSearching = true;
    });
    
    await Future.delayed(const Duration(seconds: 2));
    
    setState(() {
      _searchResults = [
        {'type': 'Match', 'indicator': _searchController.text, 'source': 'ThreatIntel Feed', 'severity': 'High', 'timestamp': DateTime.now().toString()},
        {'type': 'Related', 'indicator': 'Associated Domain', 'source': 'DNS Logs', 'severity': 'Medium', 'timestamp': DateTime.now().toString()},
      ];
      _isSearching = false;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Threat Hunting'),
        actions: [
          IconButton(
            icon: const Icon(Icons.history),
            onPressed: () {},
          ),
        ],
      ),
      body: Column(
        children: [
          Container(
            padding: const EdgeInsets.all(16),
            color: Colors.blue.shade50,
            child: Column(
              children: [
                Row(
                  children: [
                    Expanded(
                      child: Container(
                        decoration: BoxDecoration(
                          color: Colors.white,
                          borderRadius: BorderRadius.circular(8),
                        ),
                        child: TextField(
                          controller: _searchController,
                          decoration: InputDecoration(
                            hintText: 'Enter IP, Domain, Hash, or URL...',
                            prefixIcon: const Icon(Icons.search),
                            border: OutlineInputBorder(
                              borderRadius: BorderRadius.circular(8),
                              borderSide: BorderSide.none,
                            ),
                            filled: true,
                            fillColor: Colors.white,
                          ),
                          onSubmitted: (_) => _performHunt(),
                        ),
                      ),
                    ),
                    const SizedBox(width: 12),
                    SizedBox(
                      width: 120,
                      child: DropdownButtonFormField<String>(
                        value: _selectedHuntType,
                        items: const [
                          DropdownMenuItem(value: 'IOC', child: Text('IOC Search')),
                          DropdownMenuItem(value: 'Behavior', child: Text('Behavioral')),
                          DropdownMenuItem(value: 'Anomaly', child: Text('Anomaly')),
                        ],
                        onChanged: (value) => setState(() => _selectedHuntType = value!),
                        decoration: const InputDecoration(
                          filled: true,
                          fillColor: Colors.white,
                          border: OutlineInputBorder(
                            borderRadius: BorderRadius.zero,
                            borderSide: BorderSide.none,
                          ),
                        ),
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 12),
                SizedBox(
                  width: double.infinity,
                  child: ElevatedButton(
                    onPressed: _performHunt,
                    child: const Text('Start Hunt'),
                  ),
                ),
              ],
            ),
          ),
          if (_isSearching)
            const Padding(
              padding: EdgeInsets.all(32),
              child: Column(
                children: [
                  CircularProgressIndicator(),
                  SizedBox(height: 16),
                  Text('Hunting for threats...'),
                ],
              ),
            ),
          if (_searchResults.isNotEmpty)
            Expanded(
              child: ListView.builder(
                padding: const EdgeInsets.all(16),
                itemCount: _searchResults.length,
                itemBuilder: (context, index) {
                  final result = _searchResults[index];
                  return Card(
                    margin: const EdgeInsets.only(bottom: 12),
                    child: ListTile(
                      leading: CircleAvatar(
                        backgroundColor: result['severity'] == 'High' ? Colors.red : Colors.orange,
                        child: const Icon(Icons.warning, color: Colors.white),
                      ),
                      title: Text(result['indicator'], style: const TextStyle(fontWeight: FontWeight.bold)),
                      subtitle: Text('Source: ${result['source']} • ${result['timestamp'].substring(0, 19)}'),
                      trailing: Chip(
                        label: Text(result['severity']),
                        backgroundColor: result['severity'] == 'High' ? Colors.red.shade100 : Colors.orange.shade100,
                      ),
                      onTap: () {},
                    ),
                  );
                },
              ),
            ),
          if (_searchResults.isEmpty && !_isSearching)
            Expanded(
              child: Center(
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    const Icon(Icons.search_off, size: 64, color: Colors.grey),
                    const SizedBox(height: 16),
                    const Text('No results found'),
                    const SizedBox(height: 8),
                    Text(
                      'Try searching for IP addresses, domains, or file hashes',
                      style: TextStyle(color: Colors.grey.shade600),
                    ),
                  ],
                ),
              ),
            ),
        ],
      ),
      bottomNavigationBar: Container(
        padding: const EdgeInsets.all(16),
        color: Colors.grey.shade100,
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text('IOC Library', style: TextStyle(fontWeight: FontWeight.bold)),
            const SizedBox(height: 8),
            SizedBox(
              height: 100,
              child: ListView.builder(
                scrollDirection: Axis.horizontal,
                itemCount: _iocLibrary.length,
                itemBuilder: (context, index) {
                  final ioc = _iocLibrary[index];
                  return Container(
                    width: 200,
                    margin: const EdgeInsets.only(right: 12),
                    padding: const EdgeInsets.all(12),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(8),
                      border: Border.all(color: Colors.grey.shade300),
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Row(
                          children: [
                            Text(ioc['type'], style: const TextStyle(fontWeight: FontWeight.bold)),
                            const Spacer(),
                            Container(
                              padding: const EdgeInsets.symmetric(horizontal: 4, vertical: 2),
                              decoration: BoxDecoration(
                                color: ioc['confidence'] == 'Critical' ? Colors.red.shade100 : Colors.orange.shade100,
                                borderRadius: BorderRadius.circular(4),
                              ),
                              child: Text(ioc['confidence'], style: const TextStyle(fontSize: 10)),
                            ),
                          ],
                        ),
                        const SizedBox(height: 4),
                        Text(ioc['value'], style: const TextStyle(fontSize: 12), maxLines: 2),
                        const SizedBox(height: 4),
                        Text(ioc['threat'], style: const TextStyle(fontSize: 11, color: Colors.grey)),
                      ],
                    ),
                  );
                },
              ),
            ),
          ],
        ),
      ),
    );
  }
}