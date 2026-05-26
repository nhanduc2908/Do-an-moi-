import 'package:flutter/material.dart';

class RiskAssessmentScreen extends StatefulWidget {
  const RiskAssessmentScreen({super.key});

  @override
  State<RiskAssessmentScreen> createState() => _RiskAssessmentScreenState();
}

class _RiskAssessmentScreenState extends State<RiskAssessmentScreen> {
  int _selectedLikelihood = 3;
  int _selectedImpact = 3;
  final List<Map<String, dynamic>> _assessments = [
    {'asset': 'Customer Database', 'risk': 'Data Breach', 'likelihood': 4, 'impact': 5, 'score': 20, 'date': '2024-01-10'},
    {'asset': 'Web Application', 'risk': 'SQL Injection', 'likelihood': 3, 'impact': 4, 'score': 12, 'date': '2024-01-08'},
    {'asset': 'Network Infrastructure', 'risk': 'DDoS Attack', 'likelihood': 2, 'impact': 5, 'score': 10, 'date': '2024-01-05'},
  ];

  int get _riskScore => _selectedLikelihood * _selectedImpact;

  String get _riskLevel {
    if (_riskScore >= 15) return 'Critical';
    if (_riskScore >= 8) return 'High';
    if (_riskScore >= 4) return 'Medium';
    return 'Low';
  }

  Color get _riskColor {
    if (_riskScore >= 15) return Colors.red;
    if (_riskScore >= 8) return Colors.orange;
    if (_riskScore >= 4) return Colors.yellow.shade700;
    return Colors.green;
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Risk Assessment'),
        actions: [
          IconButton(
            icon: const Icon(Icons.save),
            onPressed: () {},
          ),
        ],
      ),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          Card(
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('New Risk Assessment', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 16),
                  TextField(
                    decoration: const InputDecoration(labelText: 'Asset Name'),
                  ),
                  const SizedBox(height: 12),
                  TextField(
                    decoration: const InputDecoration(labelText: 'Risk Description'),
                    maxLines: 3,
                  ),
                  const SizedBox(height: 12),
                  DropdownButtonFormField<String>(
                    items: const [
                      DropdownMenuItem(value: 'Security', child: Text('Security')),
                      DropdownMenuItem(value: 'Operational', child: Text('Operational')),
                      DropdownMenuItem(value: 'Compliance', child: Text('Compliance')),
                    ],
                    onChanged: (value) {},
                    decoration: const InputDecoration(labelText: 'Risk Category'),
                  ),
                ],
              ),
            ),
          ),
          const SizedBox(height: 16),
          Card(
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('Risk Scoring', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 16),
                  const Text('Likelihood', style: TextStyle(fontWeight: FontWeight.bold)),
                  Slider(
                    value: _selectedLikelihood.toDouble(),
                    min: 1,
                    max: 5,
                    divisions: 4,
                    label: _selectedLikelihood.toString(),
                    onChanged: (value) => setState(() => _selectedLikelihood = value.toInt()),
                  ),
                  const SizedBox(height: 16),
                  const Text('Impact', style: TextStyle(fontWeight: FontWeight.bold)),
                  Slider(
                    value: _selectedImpact.toDouble(),
                    min: 1,
                    max: 5,
                    divisions: 4,
                    label: _selectedImpact.toString(),
                    onChanged: (value) => setState(() => _selectedImpact = value.toInt()),
                  ),
                  const SizedBox(height: 24),
                  Container(
                    padding: const EdgeInsets.all(16),
                    decoration: BoxDecoration(
                      color: _riskColor.withOpacity(0.1),
                      borderRadius: BorderRadius.circular(8),
                      border: Border.all(color: _riskColor),
                    ),
                    child: Column(
                      children: [
                        Text('Risk Score: $_riskScore / 25', style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold)),
                        const SizedBox(height: 8),
                        Text('Risk Level: $_riskLevel', style: TextStyle(color: _riskColor, fontWeight: FontWeight.bold)),
                      ],
                    ),
                  ),
                  const SizedBox(height: 16),
                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton(
                      onPressed: () {},
                      child: const Text('Save Assessment'),
                    ),
                  ),
                ],
              ),
            ),
          ),
          const SizedBox(height: 16),
          const Text('Recent Assessments', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
          const SizedBox(height: 8),
          ..._assessments.map((assessment) => Card(
            margin: const EdgeInsets.only(bottom: 12),
            child: ListTile(
              leading: CircleAvatar(
                backgroundColor: _getScoreColor(assessment['score']),
                child: Text(assessment['score'].toString(), style: const TextStyle(color: Colors.white, fontSize: 12)),
              ),
              title: Text(assessment['risk'], style: const TextStyle(fontWeight: FontWeight.bold)),
              subtitle: Text('${assessment['asset']} • ${assessment['date']}'),
              trailing: const Icon(Icons.chevron_right),
              onTap: () {},
            ),
          )),
        ],
      ),
    );
  }

  Color _getScoreColor(int score) {
    if (score >= 15) return Colors.red;
    if (score >= 8) return Colors.orange;
    return Colors.green;
  }
}