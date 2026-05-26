import 'package:flutter/material.dart';
import 'package:fl_chart/fl_chart.dart';

class ThreatIntelligenceScreen extends StatefulWidget {
  const ThreatIntelligenceScreen({super.key});

  @override
  State<ThreatIntelligenceScreen> createState() => _ThreatIntelligenceScreenState();
}

class _ThreatIntelligenceScreenState extends State<ThreatIntelligenceScreen> {
  int _selectedTab = 0; // 0: Feeds, 1: Reports, 2: Indicators
  
  final List<Map<String, dynamic>> _threatFeeds = [
    {'name': 'AlienVault OTX', 'status': 'Connected', 'lastUpdate': '2024-01-15 10:30', 'indicators': 1245, 'type': 'Open Threat Exchange'},
    {'name': 'IBM X-Force', 'status': 'Connected', 'lastUpdate': '2024-01-15 09:15', 'indicators': 892, 'type': 'Commercial'},
    {'name': 'MISP', 'status': 'Connected', 'lastUpdate': '2024-01-14 23:00', 'indicators': 3456, 'type': 'Open Source'},
    {'name': 'CrowdStrike', 'status': 'Connected', 'lastUpdate': '2024-01-15 08:45', 'indicators': 567, 'type': 'Commercial'},
    {'name': 'VirusTotal', 'status': 'Connected', 'lastUpdate': '2024-01-15 11:00', 'indicators': 2341, 'type': 'Free'},
  ];

  final List<Map<String, dynamic>> _threatReports = [
    {'title': 'Quarterly Threat Report Q4 2024', 'date': '2024-01-10', 'summary': 'Analysis of emerging threats and attack patterns', 'severity': 'High'},
    {'title': 'Ransomware Trends Analysis', 'date': '2024-01-05', 'summary': 'New ransomware variants and defense strategies', 'severity': 'Critical'},
    {'title': 'APT Group Activity Report', 'date': '2023-12-28', 'summary': 'Latest APT campaigns and TTPs', 'severity': 'High'},
    {'title': 'Phishing Campaign Analysis', 'date': '2023-12-20', 'summary': 'Recent phishing tactics and targets', 'severity': 'Medium'},
  ];

  final List<Map<String, dynamic>> _latestIndicators = [
    {'type': 'IP', 'value': '185.130.5.253', 'source': 'AlienVault', 'confidence': 'High', 'date': '2024-01-15'},
    {'type': 'Domain', 'value': 'malware-domain.com', 'source': 'IBM X-Force', 'confidence': 'High', 'date': '2024-01-15'},
    {'type': 'Hash', 'value': 'e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855', 'source': 'MISP', 'confidence': 'Critical', 'date': '2024-01-14'},
    {'type': 'URL', 'value': 'http://evil.com/payload', 'source': 'VirusTotal', 'confidence': 'Medium', 'date': '2024-01-14'},
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Threat Intelligence'),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh),
            onPressed: () {},
          ),
          IconButton(
            icon: const Icon(Icons.settings),
            onPressed: () {},
          ),
        ],
      ),
      body: Column(
        children: [
          // Tab Bar
          Container(
            color: Colors.grey.shade100,
            child: Row(
              children: [
                _buildTab('Threat Feeds', 0),
                _buildTab('Reports', 1),
                _buildTab('Indicators', 2),
              ],
            ),
          ),
          // Content
          Expanded(
            child: IndexedStack(
              index: _selectedTab,
              children: [
                _buildFeedsTab(),
                _buildReportsTab(),
                _buildIndicatorsTab(),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildTab(String title, int index) {
    final isSelected = _selectedTab == index;
    return Expanded(
      child: InkWell(
        onTap: () => setState(() => _selectedTab = index),
        child: Container(
          padding: const EdgeInsets.symmetric(vertical: 12),
          decoration: BoxDecoration(
            border: Border(
              bottom: BorderSide(
                color: isSelected ? Colors.blue : Colors.transparent,
                width: 2,
              ),
            ),
          ),
          child: Text(
            title,
            textAlign: TextAlign.center,
            style: TextStyle(
              fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
              color: isSelected ? Colors.blue : Colors.grey,
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildFeedsTab() {
    return ListView.builder(
      padding: const EdgeInsets.all(16),
      itemCount: _threatFeeds.length,
      itemBuilder: (context, index) {
        final feed = _threatFeeds[index];
        return Card(
          margin: const EdgeInsets.only(bottom: 12),
          child: ExpansionTile(
            leading: Container(
              width: 50,
              height: 50,
              decoration: BoxDecoration(
                color: _getFeedColor(feed['name']).withOpacity(0.1),
                borderRadius: BorderRadius.circular(8),
              ),
              child: Icon(_getFeedIcon(feed['name']), color: _getFeedColor(feed['name'])),
            ),
            title: Text(feed['name'], style: const TextStyle(fontWeight: FontWeight.bold)),
            subtitle: Text('${feed['type']} • ${feed['indicators']} indicators'),
            trailing: Container(
              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
              decoration: BoxDecoration(
                color: feed['status'] == 'Connected' ? Colors.green.shade100 : Colors.red.shade100,
                borderRadius: BorderRadius.circular(12),
              ),
              child: Text(
                feed['status'],
                style: TextStyle(color: feed['status'] == 'Connected' ? Colors.green : Colors.red, fontSize: 12),
              ),
            ),
            children: [
              Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    _buildDetailRow('Last Update', feed['lastUpdate']),
                    const SizedBox(height: 8),
                    _buildDetailRow('Total Indicators', feed['indicators'].toString()),
                    const SizedBox(height: 8),
                    _buildDetailRow('Feed Type', feed['type']),
                    const SizedBox(height: 16),
                    Row(
                      children: [
                        Expanded(
                          child: OutlinedButton(
                            onPressed: () {},
                            child: const Text('View Indicators'),
                          ),
                        ),
                        const SizedBox(width: 12),
                        Expanded(
                          child: ElevatedButton(
                            onPressed: () {},
                            child: const Text('Sync Now'),
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            ],
          ),
        );
      },
    );
  }

  Widget _buildReportsTab() {
    return ListView.builder(
      padding: const EdgeInsets.all(16),
      itemCount: _threatReports.length,
      itemBuilder: (context, index) {
        final report = _threatReports[index];
        return Card(
          margin: const EdgeInsets.only(bottom: 12),
          child: ListTile(
            leading: CircleAvatar(
              backgroundColor: report['severity'] == 'Critical' ? Colors.red : Colors.orange,
              child: const Icon(Icons.description, color: Colors.white),
            ),
            title: Text(report['title'], style: const TextStyle(fontWeight: FontWeight.bold)),
            subtitle: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(report['summary']),
                const SizedBox(height: 4),
                Text('Published: ${report['date']}', style: const TextStyle(fontSize: 12, color: Colors.grey)),
              ],
            ),
            trailing: const Icon(Icons.chevron_right),
            onTap: () {},
          ),
        );
      },
    );
  }

  Widget _buildIndicatorsTab() {
    return Column(
      children: [
        Container(
          padding: const EdgeInsets.all(16),
          color: Colors.grey.shade100,
          child: Row(
            children: [
              Expanded(
                child: TextField(
                  decoration: InputDecoration(
                    hintText: 'Search indicators...',
                    prefixIcon: const Icon(Icons.search),
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(8),
                      backgroundColor: Colors.white,
                    ),
                  ),
                ),
              ),
              const SizedBox(width: 12),
              IconButton(
                icon: const Icon(Icons.filter_list),
                onPressed: () {},
              ),
            ],
          ),
        ),
        Expanded(
          child: ListView.builder(
            padding: const EdgeInsets.all(16),
            itemCount: _latestIndicators.length,
            itemBuilder: (context, index) {
              final indicator = _latestIndicators[index];
              return Card(
                margin: const EdgeInsets.only(bottom: 12),
                child: ListTile(
                  leading: Container(
                    width: 50,
                    height: 50,
                    decoration: BoxDecoration(
                      color: _getIndicatorColor(indicator['type']).withOpacity(0.1),
                      borderRadius: BorderRadius.circular(8),
                    ),
                    child: Center(
                      child: Text(
                        indicator['type'],
                        style: TextStyle(color: _getIndicatorColor(indicator['type']), fontWeight: FontWeight.bold),
                      ),
                    ),
                  ),
                  title: Text(indicator['value'], style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14)),
                  subtitle: Text('Source: ${indicator['source']} • ${indicator['date']}'),
                  trailing: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                    decoration: BoxDecoration(
                      color: indicator['confidence'] == 'Critical' ? Colors.red.shade100 : 
                             (indicator['confidence'] == 'High' ? Colors.orange.shade100 : Colors.yellow.shade100),
                      borderRadius: BorderRadius.circular(12),
                    ),
                    child: Text(
                      indicator['confidence'],
                      style: TextStyle(
                        color: indicator['confidence'] == 'Critical' ? Colors.red : 
                               (indicator['confidence'] == 'High' ? Colors.orange : Colors.orange),
                        fontSize: 12,
                      ),
                    ),
                  ),
                  onTap: () {},
                ),
              );
            },
          ),
        ),
      ],
    );
  }

  Widget _buildDetailRow(String label, String value) {
    return Row(
      children: [
        SizedBox(width: 100, child: Text(label, style: const TextStyle(fontWeight: FontWeight.bold, color: Colors.grey))),
        Expanded(child: Text(value)),
      ],
    );
  }

  Color _getFeedColor(String feedName) {
    switch (feedName) {
      case 'AlienVault OTX': return Colors.blue;
      case 'IBM X-Force': return Colors.red;
      case 'MISP': return Colors.green;
      case 'CrowdStrike': return Colors.purple;
      default: return Colors.orange;
    }
  }

  IconData _getFeedIcon(String feedName) {
    switch (feedName) {
      case 'AlienVault OTX': return Icons.security;
      case 'IBM X-Force': return Icons.business;
      case 'MISP': return Icons.share;
      default: return Icons.cloud;
    }
  }

  Color _getIndicatorColor(String type) {
    switch (type) {
      case 'IP': return Colors.blue;
      case 'Domain': return Colors.green;
      case 'Hash': return Colors.orange;
      default: return Colors.purple;
    }
  }
}