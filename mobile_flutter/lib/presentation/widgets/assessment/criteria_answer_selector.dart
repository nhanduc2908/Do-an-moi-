// Đường dẫn: mobile_flutter/lib/presentation/widgets/assessment/criteria_answer_selector.dart

import 'package:flutter/material.dart';

class CriteriaAnswerSelector extends StatefulWidget {
  final int maxScore;
  final Function(int) onScoreSelected;

  const CriteriaAnswerSelector({
    super.key,
    required this.maxScore,
    required this.onScoreSelected,
  });

  @override
  State<CriteriaAnswerSelector> createState() => _CriteriaAnswerSelectorState();
}

class _CriteriaAnswerSelectorState extends State<CriteriaAnswerSelector> {
  int _selectedScore = -1;

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        const Text('Select Score:', style: TextStyle(fontWeight: FontWeight.bold)),
        const SizedBox(height: 8),
        Wrap(
          spacing: 8,
          children: List.generate(widget.maxScore + 1, (index) {
            final isSelected = _selectedScore == index;
            return ChoiceChip(
              label: Text(index.toString()),
              selected: isSelected,
              onSelected: (selected) {
                setState(() => _selectedScore = index);
                widget.onScoreSelected(index);
              },
              selectedColor: Colors.blue,
              labelStyle: TextStyle(color: isSelected ? Colors.white : null),
            );
          }),
        ),
      ],
    );
  }
}