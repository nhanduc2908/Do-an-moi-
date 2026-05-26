import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../providers/assessment_provider.dart';
import '../../widgets/common/custom_button.dart';

class SuggestCriteriaScreen extends ConsumerStatefulWidget {
  const SuggestCriteriaScreen({super.key});

  @override
  ConsumerState<SuggestCriteriaScreen> createState() => _SuggestCriteriaScreenState();
}

class _SuggestCriteriaScreenState extends ConsumerState<SuggestCriteriaScreen> {
  final TextEditingController _requirementsController = TextEditingController();
  List<Map<String, dynamic>> _suggestions = [];
  bool _isGenerating = false;

  Future<void> _generateSuggestions() async {
    if (_requirementsController.text.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please enter requirements')),
      );
      return;
    }

    setState(() {
      _isGenerating = true;
      _suggestions = [];
    });
    
    await Future.delayed(const Duration(seconds: 2));
    
    setState(() {
      _isGenerating = false;
      _suggestions = [
        {'name': 'Access Control Policy', 'description': 'Implement formal access control policy', 'confidence': 95},
        {'name': 'Data Encryption', 'description': 'Encrypt sensitive data at rest and in transit', 'confidence': 92},
        {'name': 'Regular Audits', 'description': 'Conduct regular security audits', 'confidence': 88},
      ];
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('AI Criteria Suggestions'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            TextField(
              controller: _requirementsController,
              decoration: const InputDecoration(
                labelText: 'Security Requirements',
                hintText: 'Describe your security requirements...',
                border: OutlineInputBorder(),
              ),
              maxLines: 5,
            ),
            const SizedBox(height: 16),
            CustomButton(
              text: 'Generate Suggestions',
              onPressed: _generateSuggestions,
              isLoading: _isGenerating,
            ),
            const SizedBox(height: 16),
            if (_suggestions.isNotEmpty) ...[
              const Text('AI Suggestions', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
              const SizedBox(height: 8),
              ..._suggestions.map((suggestion) => Card(
                margin: const EdgeInsets.only(bottom: 8),
                child: ListTile(
                  title: Text(suggestion['name'], style: const TextStyle(fontWeight: FontWeight.bold)),
                  subtitle: Text(suggestion['description']),
                  trailing: Chip(
                    label: Text('${suggestion['confidence']}%'),
                    backgroundColor: Colors.blue.shade100,
                  ),
                  onTap: () {},
                ),
              )),
            ],
          ],
        ),
      ),
    );
  }
}